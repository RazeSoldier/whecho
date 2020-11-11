<template>
    <div>
        <small>高亮的星系表示24小时内出现过2次以上不一样的流浪洞</small>
        <el-table
            :data="tableData"
            v-loading="loading"
            class="backtop"
            :cell-style="cellStyle"
            :row-style="rowStyle">
            <el-table-column prop="region" label="星域" width="90px" sortable :filters="regionFilters" :filter-method="regionFilter">
            </el-table-column>
            <el-table-column prop="system" label="星系" width="100px" sortable>
            </el-table-column>
            <el-table-column label="洞口" prop="info" sortable :filters="signatureNameFilters" :filter-method="signatureNameFilter">
                <template slot-scope="scope">
                    <span v-if="scope.row.info !== ''">
                        <span>{{ scope.row.info.signature_name }}，提交人：{{ scope.row.info.submitter }}，提交时间：{{ scope.row.info.time }}</span>
                    </span>
                    <span style="float: right">
                        <el-button size="mini" @click="showHistory(scope.row.system)">显示历史记录</el-button>
                    </span>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog :title="dialogTitle" :visible.sync="historyTableVisible" :v-loading="dialogLoading">
            <el-table :data="historyData" :default-sort = "{prop: 'submit_time', order: 'descending'}">
                <el-table-column label="提交时间" prop="submit_time" sortable>
                </el-table-column>
                <el-table-column label="洞口编号" prop="signature_name">
                </el-table-column>
                <el-table-column label="备注" prop="notice">
                </el-table-column>
                <el-table-column label="提交人" prop="submitter">
                </el-table-column>
                <el-table-column label="操作">
                    <template slot-scope="scope">
                        <el-button
                            size="mini"
                            type="danger"
                            @click="deleteReport(scope.row.id)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-dialog>
    </div>
</template>

<script>
export default {
    name: "DriftersWormholeStateTable",
    props: ['stateFetchUrl', 'historyFetchUrl', 'deleteReportUrl'],
    data() {
        return {
            tableData: [],
            loading: true,
            requestInit: {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                }
            },
            historyCache: {},
            historyTableVisible: false,
            historySystemName: null,
            dialogLoading: false,
            historyData: [],
            cellStyle: {
                padding: '5px 0'
            },
            signatureNameFilters: [
                {text: '无流浪洞', value: '无流浪洞'},
                {text: 'V928', value: 'V928'},
                {text: 'R259', value: 'R259'},
                {text: 'S877', value: 'S877'},
                {text: 'B735', value: 'B735'},
                {text: 'C414', value: 'C414'},
            ],
            regionFilters: [
                {text: '对舞', value: '对舞'},
                {text: '静寂谷', value: '静寂谷'},
                {text: '特布特', value: '特布特'},
                {text: '黑渊', value: '黑渊'},
                {text: '维纳尔', value: '维纳尔'},
                {text: '血脉', value: '血脉'},
                {text: '特纳', value: '特纳'},
            ],
        }
    },
    mounted() {
        fetch(this.stateFetchUrl, this.requestInit).then(res => res.json()).then(json => {
            this.tableData = json;
            this.loading = false;
        });
    },
    methods: {
        showHistory(system) {
            this.historyTableVisible = true;
            if (this.historyCache.hasOwnProperty(system)) {
                this.historyData = this.historyCache[system];
                this.historySystemName = system;
                return;
            }
            this.dialogLoading = true;
            fetch(this.historyFetchUrl + '/' + system, this.requestInit).then(res => res.json()).then(json => {
                this.historySystemName = system;
                this.historyCache[system] = json.data;
                this.historyData = json.data;
                this.dialogLoading = false;
            });
        },
        deleteReport(reportId) {
            fetch(this.deleteReportUrl + '/' + reportId, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            }).then(res => res.json()).then(json => {
                if (json.status === 'ok') {
                    for (const key in this.historyData) {
                        if (this.historyData[key].id === reportId) {
                            this.historyData.splice(key, 1);
                        }
                    }
                    this.$message.success('删除成功');
                }
            });
        },
        rowStyle({row}) {
            if (row.isMultiple) {
                return {
                    background: 'oldlace',
                };
            }
        },
        signatureNameFilter(value, row) {
            if (row.info !== '') {
                return value === row.info.signature_name;
            }
            return false;
        },
        regionFilter(value, row) {
            return value === row.region;
        }
    },
    computed: {
        dialogTitle() {
            return this.historySystemName + '的历史记录';
        }
    }
}
</script>

<style scoped>

</style>

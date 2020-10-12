<template>
    <div>
        <el-table :data="tableData" v-loading="loading" class="backtop">
            <el-table-column prop="region" label="星域" width="80px" sortable>
            </el-table-column>
            <el-table-column prop="system" label="星系" width="100px" sortable>
            </el-table-column>
            <el-table-column label="洞口" prop="info" sortable>
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

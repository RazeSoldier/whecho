<template>
    <el-form :model="form" v-loading="loading" label-width="80px">
        <p>本页面用于报告流浪洞的情况</p>
        <el-form-item label="星系">
            <el-cascader v-model="form.system" :options="systemOptions" :props="{ expandTrigger: 'hover' }" filterable>
            </el-cascader>
        </el-form-item>
        <el-form-item label="洞口编号">
            <el-select v-model="form.signatureName" filterable>
                <el-option
                    v-for="item in signatureList"
                    :key="item"
                    :label="item"
                    :value="item">
                </el-option>
            </el-select>
        </el-form-item>
        <el-form-item label="提交人">
            <el-input v-model="form.submitter" maxlength="40" show-word-limit></el-input>
        </el-form-item>
        <el-form-item label="备注">
            <el-input v-model="form.notice" maxlength="50" show-word-limit></el-input>
        </el-form-item>
        <el-form-item>
            <el-button type="primary" @click="submit">提 交</el-button>
        </el-form-item>
    </el-form>
</template>

<script>
export default {
    name: "WormholeReportForm",
    props: ['systemOptions', 'signatureList', 'reportUrl'],
    data() {
        return {
            form: {
                system: '',
                signatureName: '',
                submitter: '',
                notice: '',
            },
            loading: false,
        }
    },
    methods: {
        submit() {
            const msg = this.checkForm();
            if (msg !== null) {
                this.$message.warning(msg);
                return;
            }
            this.loading = true;
            fetch(this.reportUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                body: JSON.stringify(this.form),
            }).then(res => res.json()).then(json => {
                if (json.hasOwnProperty('errors')) {
                    this.$message.warning(json.message);
                    this.loading = false;
                    return;
                }
                if (json.status === 'ok') {
                    this.loading = false;
                    this.$message.success('成功提交');
                    this.resetForm();
                }
            });
        },
        checkForm() {
            if (this.form.system === '') {
                return '请选择星系';
            }
            if (this.form.signatureName === '') {
                return '请选择洞口编号';
            }
            if (this.form.submitter === '') {
                return '填写人为空';
            }
            return null;
        },
        resetForm() {
            this.form.system = '';
            this.form.signatureName = '';
            this.form.submitter = '';
            this.form.notice = '';
        }
    }
}
</script>

<style scoped>

</style>

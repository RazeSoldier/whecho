@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-start">
        <div class="col-md-8">
            <el-tabs>
                <el-tab-pane label="流浪洞情况">
                    <drifters-wh-state-table
                        state-fetch-url="{{route('drifters-wormhole-state')}}"
                        history-fetch-url="{{route('system-history')}}"
                        delete-report-url="{{route('delete-report')}}">
                    </drifters-wh-state-table>
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
</div>
@endsection

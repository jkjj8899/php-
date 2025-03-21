@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <form class="layui-form" action="">

        <div class="layui-form-item">
            <label class="layui-form-label">秒数</label>
            <div class="layui-input-block">
                <input type="text" name="seconds" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->seconds}}">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">最低投注</label>
            <div class="layui-input-block">
                <input type="text" name="mixtz" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->mixtz}}">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="checkbox" name="status" value="{{$result->status}}" lay-skin="switch" lay-text="是|否"  {{ $result->status == 1 ? 'checked' : '' }}>

            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">收益率</label>
            <div class="layui-input-block">
                <input type="text" name="profit_ratio" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->profit_ratio}}">
            </div>
        </div>

        <input type="hidden" name="id" value="{{$result->id}}">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script>

        layui.use(['form','laydate'],function () {
            var form = layui.form
                ,$ = layui.jquery
                ,laydate = layui.laydate
                ,index = parent.layer.getFrameIndex(window.name);
            //监听提交
            form.on('submit(demo1)', function(data){
                var data = data.field;
                $.ajax({
                    url:'{{url('admin/micro_seconds_add')}}'
                    ,type:'post'
                    ,dataType:'json'
                    ,data : data
                    ,success:function(res){
                        if (res.type=='error') {
                            layer.msg(res.message);
                        } else {
                            parent.layer.close(index);
                            parent.window.location.reload();
                        }
                    }
                });
                return false;
            });
        });
    </script>

@endsection
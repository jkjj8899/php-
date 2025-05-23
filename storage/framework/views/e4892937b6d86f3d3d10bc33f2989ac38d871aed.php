<?php $__env->startSection('page-head'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-content'); ?>
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">产品名</label>
            <div class="layui-input-block">
                <input type="text" name="wealth_name" autocomplete="off" placeholder="" class="layui-input" value="" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">期限</label>
            <div class="layui-input-block">
                <input type="text" name="period" autocomplete="off" placeholder="单位：天" class="layui-input" value="" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">收益率</label>
            <div class="layui-input-block" style="display: flex">
                <input type="text" style="width: 100px" name="min_daily_return_rate" autocomplete="off" placeholder="最小收益率" class="layui-input" value="">
                <span style="font-size: 25px;line-height: 40px; margin-left: 10px;margin-right: 10px;"> - </span>
                <input type="text" style="width: 100px;" name="max_daily_return_rate" autocomplete="off" placeholder="最大收益率" class="layui-input" value="">
                <span style="font-size: 14px; line-height: 40px; margin-left: 10px;">请输入小数,例如40%应填0.4</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">锁仓本金</label>
            <div class="layui-input-block" style="display: flex">
                <input type="text" style="width: 100px" name="min_single_limit" autocomplete="off" placeholder="锁仓本金" class="layui-input" value="">
                <span style="font-size: 25px; line-height: 40px; display: none; margin-left: 10px;margin-right: 10px;"> - </span>
                <input type="text" style="width: 100px; display: none;" name="max_single_limit" autocomplete="off" placeholder="最大限额" class="layui-input" value="10000000000">
            </div>
        </div>
        <div class="layui-form-item hide">
            <label class="layui-form-label">违约金比例</label>
            <div class="layui-input-block" style="display: flex">
                <input type="text" style="width: 100px" name="reneged" autocomplete="off" placeholder="" class="layui-input" value="">
                <span style="font-size: 14px; line-height: 40px; margin-left: 10px;">请输入小数,例如40%应填0.4 违约金
=违约结算比例*剩余天数*投资数量。</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">币种</label>
            <div class="layui-input-block" style="display: flex">
                <select name="currency" lay-filter="" lay-search>
                    <option value=""></option>
                    <?php if(!empty($currencies)): ?>
                        <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($currency->id); ?>"><?php echo e($currency->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
                <span style="font-size: 14px; line-height: 40px; margin-left: 10px;">选择币种</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">每个用户限购次数</label>
            <div class="layui-input-block" style="display: flex">
                <input type="text" style="width: 100px" name="nlimit" autocomplete="off" placeholder="" class="layui-input" value="">
                <span style="font-size: 14px; line-height: 40px; margin-left: 10px;">请输入整数</span>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="add">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>


        layui.use(['form','laydate'],function () {
            var form = layui.form
                ,$ = layui.jquery
                ,laydate = layui.laydate
                ,index = parent.layer.getFrameIndex(window.name);
            //监听提交
            form.on('submit(add)', function(data){
                var data = data.field;
                $.ajax({
                    url:'<?php echo e(url('admin/bztl/product/add')); ?>'
                    ,type:'post'
                    ,dataType:'json'
                    ,data : data
                    ,success:function(res){
                        if(res.type=='error'){
                            layer.msg(res.message);
                        }else{
                            parent.layer.close(index);
                            parent.window.location.reload();
                        }
                    }
                });
                return false;
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin._layoutNew', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
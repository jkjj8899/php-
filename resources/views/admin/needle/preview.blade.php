@extends('admin._layoutNew')
@section('page-head')
    <script src="https://lib.baomitu.com/echarts/5.0.2/echarts.min.js"></script>
@endsection

@section('page-content')


    <div style="width:100%; height: 900px;" id="echarts"></div>

@endsection

@section('scripts')
    <script type="text/javascript">

        $(function () {
            $('#echarts').height($(window).height());

            $(window).resize(()=>{
                $('#echarts').height($(window).height());
            })
        });
        let layer;
        let form;
        let element;
        layui.use(['element','laydate', 'layer','form'], () => {
            var laydate = layui.laydate;
            let dropdown = layui.dropdown;
            layer = layui.layer;
            form=layui.form;
            element =layui.element;
            laydate.render({
                elem: '#dates',
                type: 'datetime',
                format:'yyyy-MM-dd HH:mm'
            });
            previewKLine();
        })

        let data = [];



        function previewKLine() {


            var upColor = '#ec0000';
            var upBorderColor = '#8A0000';
            var downColor = '#00da3c';
            var downBorderColor = '#008F28';

            var dataCount = 60;
            var myChart = echarts.init(document.getElementById('echarts'));

            var option = {
                dataset: {
                    source: <?php echo json_encode($data); ?>
                },
                title: {
                    text: 'Data Amount: ' + echarts.format.addCommas(dataCount)
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'line'
                    }
                },
                toolbox: {
                    feature: {
                        dataZoom: {
                            yAxisIndex: false
                        },
                    }
                },
                grid: [
                    {
                        left: '10%',
                        right: '10%',
                        bottom: 200
                    },
                    {
                        left: '10%',
                        right: '10%',
                        height: 80,
                        bottom: 80
                    }
                ],
                xAxis: [
                    {
                        type: 'category',
                        scale: true,
                        boundaryGap: false,
                        // inverse: true,
                        axisLine: {onZero: false},
                        splitLine: {show: false},
                        splitNumber: 20,
                        min: 'dataMin',
                        max: 'dataMax'
                    },
                    {
                        type: 'category',
                        gridIndex: 1,
                        scale: true,
                        boundaryGap: false,
                        axisLine: {onZero: false},
                        axisTick: {show: false},
                        splitLine: {show: false},
                        axisLabel: {show: false},
                        splitNumber: 20,
                        min: 'dataMin',
                        max: 'dataMax'
                    }
                ],
                yAxis: [
                    {
                        scale: true,
                        splitArea: {
                            show: true
                        }
                    },
                    {
                        scale: true,
                        gridIndex: 1,
                        splitNumber: 2,
                        axisLabel: {show: false},
                        axisLine: {show: false},
                        axisTick: {show: false},
                        splitLine: {show: false}
                    }
                ],
                dataZoom: [
                    {
                        type: 'inside',
                        xAxisIndex: [0, 1],
                        start: 10,
                        end: 100
                    },
                    {
                        show: true,
                        xAxisIndex: [0, 1],
                        type: 'slider',
                        bottom: 10,
                        start: 10,
                        end: 100
                    }
                ],
                visualMap: {
                    show: false,
                    seriesIndex: 1,
                    dimension: 6,
                    pieces: [{
                        value: 1,
                        color: upColor
                    }, {
                        value: -1,
                        color: downColor
                    }]
                },
                series: [
                    {
                        type: 'candlestick',
                        itemStyle: {
                            color: upColor,
                            color0: downColor,
                            borderColor: upBorderColor,
                            borderColor0: downBorderColor
                        },
                        encode: {
                            x: 0,
                            y: [1, 4, 3, 2]
                        }
                    },
                    {
                        name: 'Volumn',
                        type: 'bar',
                        xAxisIndex: 1,
                        yAxisIndex: 1,
                        itemStyle: {
                            color: '#7fbe9e'
                        },
                        large: true,
                        encode: {
                            x: 0,
                            y: 5
                        }
                    }
                ]
            };

            myChart.setOption(option);
            $.getJSON('/admin/myquotation/hangqing', $('#form').serialize(), res => {
                console.log(res);
                data = res;


            })


        }

        function nextKline() {

            if ($('#dates').val() === '') {
                layer.msg('请选择日期');
                return;
            }
            if (data.length === 0) {
                layer.msg('请先生成k线');
                return;
            }

            let con = JSON.stringify(data.data);
            layer.load(2);
            $.post('/admin/user/quotation', {
                kline: con,
                date: ($('#dates').val() + ' ' + $('#times').val() + ":00:00"),
                currency:$('#currency').val()
            }, res => {
                console.log(res);
                layer.closeAll('loading');
                $('#start').val(data.data[data.data.length - 1][4]);
                if (parseInt($('#times').val()) < 23) {
                    // $('#times').val(parseInt($('#times').val()) + 1);
                } else {
                    $('#times').val(0);
                    $('#dates').val(data.next)
                }
                previewKLine();
            });


        }

        function generateOHLC(count) {
            var data = [];

            var xValue = +new Date(2011, 0, 1);
            var minute = 60 * 1000;
            var baseValue = Math.random() * 12000;
            var boxVals = new Array(4);
            var dayRange = 12;

            for (var i = 0; i < count; i++) {
                baseValue = baseValue + Math.random() * 20 - 10;

                for (var j = 0; j < 4; j++) {
                    boxVals[j] = (Math.random() - 0.5) * dayRange + baseValue;
                }
                boxVals.sort();

                var openIdx = Math.round(Math.random() * 3);
                var closeIdx = Math.round(Math.random() * 2);
                if (closeIdx === openIdx) {
                    closeIdx++;
                }
                var volumn = boxVals[3] * (1000 + Math.random() * 500);

                // ['open', 'close', 'lowest', 'highest', 'volumn']
                // [1, 4, 3, 2]
                data[i] = [
                    echarts.format.formatTime('yyyy-MM-dd\nhh:mm:ss', xValue += minute),
                    +boxVals[openIdx].toFixed(2), // open
                    +boxVals[3].toFixed(2), // highest
                    +boxVals[0].toFixed(2), // lowest
                    +boxVals[closeIdx].toFixed(2),  // close
                    volumn.toFixed(0),
                    getSign(data, i, +boxVals[openIdx], +boxVals[closeIdx], 4) // sign
                ];
            }

            return data;

            function getSign(data, dataIndex, openVal, closeVal, closeDimIdx) {
                var sign;
                if (openVal > closeVal) {
                    sign = -1;
                } else if (openVal < closeVal) {
                    sign = 1;
                } else {
                    sign = dataIndex > 0
                        // If close === open, compare with close of last record
                        ? (data[dataIndex - 1][closeDimIdx] <= closeVal ? 1 : -1)
                        // No record of previous, set to be positive
                        : 1;
                }

                return sign;
            }
        }

    </script>
@endsection

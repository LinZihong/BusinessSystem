@extends('layouts.base')

@section('title')
    Stock List
@endsection
{{-- @TODO labels and frontend shit fix--}}
@section('script')
    <script src="{{ asset('js/Chart.bundle.js') }}"></script>
    <script>

       var flag = 0;

        var receivedInfo = [], labels = [], datasets = [];
        var dataLength = 0;

        var randomScalingFactor = function () {
            return Math.round(Math.random() * 100);
        };

        function randomColor() {
            var r = Math.floor(Math.random() * 256);
            var g = Math.floor(Math.random() * 256);
            var b = Math.floor(Math.random() * 256);
            return "rgb(" + r + ',' + g + ',' + b + ")";
        }

        var colors = [];
        for (var i = 0; i < {{count($stocks)}}; i++)
        {
            colors.push(randomColor());
        }

        function information() {
            $.ajax({
                url: "{{route('stockData')}}",
                dataType: "json",
                type: "GET",
                success: function (msg) {
                    var labels = [];
                    receivedInfo = msg;
                    var points = msg[0]["all_prices"].length;
                    for (var i = 0; i < msg.length; i++) {
                        datasets[i] = {};
                        datasets[i]["lineTension"] = 0;
                        datasets[i]["label"] = msg[i]["company_name"];
                        var color = colors[i];
                        datasets[i]["borderColor"] = color;
                        datasets[i]["backgroundColor"] = color;
                        datasets[i]["fill"] = false;
                        datasets[i]["data"] = msg[i]["all_prices"];
                        if (msg[i]["all_prices"].length > points) {
                            points = msg[i]["all_prices"].length
                        }
                        datasets[i]["yAxisID"] = "y-axis";
                        // labels.push("");
                    }
                    for (var j = 0; j < points; j++) {
                        labels.push("");
                    }
                    var lineChartData = {
                        labels: labels,
                        datasets: datasets
                    };

                    var ctx = document.getElementById("stock").getContext("2d");
                    window.myLine = Chart.Line(ctx, {
                        data: lineChartData,
                        options: {
                            animation: {
                                duration: 0
                            },
                            responsive: true,
                            hoverMode: 'index',
                            stacked: false,
                            title: {
                                display: false,
                                text: 'Chart.js Line Chart - Multi Axis'
                            },
                            scales: {
                                yAxes: [{
                                    type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                                    display: true,
                                    position: "left",
                                    id: "y-axis",
                                    // grid line settings
                                    gridLines: {
                                        drawOnChartArea: true, // only want the grid lines for one axis to show up
                                    },
                                }],
                            }
                        }
                    });
//                    window.myLine.update();

                    createDom();
                },
                error: function () {
                    // alert("qubudaoDBQ");
                }
            })
        }


        function createDom() {
            $.each(receivedInfo, function () {
//                $("#"+this.id).children('.mdui-panel-item').children('.mdui-panel-item-header').children('.mdui-panel-item-summary').text(this.name);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-header').children(".s1").text("现价：" + this.current_price);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-header').children(".s2").text("当前持有股数：" + this.hand_up);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-body').children(".b5").text("当前买入价：" + this.current_buy);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-body').children(".b6").text("当前卖出价：" + this.current_sell);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-body').children(".b1").text("总数：" + this.total);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-body').children(".b2").text("分红率：" + this.dividend);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-body').children(".b3").text("卖盘剩余：" + this.sell_remain);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-body').children(".b4").text("买盘剩余：" + this.buy_remain);
                $("#" + this.id).children('.mdui-panel-item').children('.mdui-panel-item-body').children(".b7").text("风险评估：" + this.risk_evaluation);

            });
        }


        $(document).ready(function () {
            information();
            setInterval("information()", 5000);
            // createDom();
        });
    </script>

@endsection

@section('stylesheet')
    <style>

        .doc-container {
            padding-top: 30px;
            padding-bottom: 150px;
        }

        .mdui-tab {
            min-width: 40px;
        !important;
        }

        .company {
            padding: 10px;
        !important;
        }


    </style>
@endsection

@section('body')

    <div class="mdui-container doc-container">
        <div class="mdui-card-header">
            <div class="mdui-typo-display-1 mdui-text-center mdui-text-color-theme">
                Stock Information and Transaction
            </div>
        </div>
        <div class="mdui-col-md-12">
            <div class="mdui-card mdui-col-md-12">
                <canvas id="stock"></canvas>
            </div>

            <div class="mdui-card mdui-table-fluid">
                <table class="mdui-table mdui-table-hoverable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Buy</th>
                        <th>Sell</th>
                    </tr>
                    </thead>
                    <tbody id="table">
                    @foreach($stocks as $stock)
                        <tr>
                            <td id="{{$stock->id}}" class="mdui-panel" mdui-panel>
                                <div class="mdui-panel-item mdui-panel-item-open">
                                    <div class="mdui-panel-item-header">
                                        <div class="mdui-panel-item-title">
                                            {{$stock->company->name}}
                                        </div>
                                        <div class="s1 mdui-panel-item-summary">
                                            现价： {{$stock->current_price}}
                                        </div>
                                        <div class="s2 mdui-panel-item-summary">
                                            当前持有股数： {{Auth::user()->resources()->resid($stock->resource_id)->first()->amount}}
                                        </div>
                                        <i class="mdui-panel-item-arrow mdui-icon material-icons">
                                            keyboard_arrow_down
                                        </i>
                                    </div>
                                    <div class="mdui-panel-item-body">
                                        <p class="b5">
                                            当前买入价： {{$stock->buyPrice()}}
                                        </p>
                                        <p class="b6">
                                            当前卖出价： {{$stock->sellPrice()}}
                                        </p>
                                        <p class="b1">
                                            总数： {{$stock->total}}
                                        </p>
                                        <p class="b2">
                                            分红率： {{$stock->dividend}}
                                        </p>
                                        <p class="b3">
                                            卖盘剩余： {{$stock->sell_remain}}
                                        </p>
                                        <p class="b4">
                                            买盘剩余： {{$stock->buy_remain}}
                                        </p>
                                        <p class="b7">
                                            风险评估： {{$stock->riskEvaluation()}}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <form action="{{ route('buyStock') }}" method="post"> {{ csrf_field() }}
                                    <input type="hidden" name="stock_id" value={{$stock->id}}>
                                    <div class="mdui-textfield">
                                        <input class="mdui-textfield-input" type="text" name="amount" placeholder="#">
                                    </div>
                                    <button type="submit"
                                            class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">
                                        Buy
                                    </button>
                                </form>
                            </td>

                            <td>
                                <form action="{{ route('sellStock') }}" method="post"> {{ csrf_field() }}
                                    <input type="hidden" name="stock_id" value={{$stock->id}}>
                                    <div class="mdui-textfield">
                                        <input class="mdui-textfield-input" type="text" name="amount" placeholder="#">
                                    </div>
                                    <button type="submit"
                                            class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">
                                        Sell
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
    <div class="mdui-bottom-nav mdui-bottom-nav-text-auto mdui-color-indigo">
        <div class="mdui-container">
            <div class="mdui-row">
                <div class="mdui-row-lg-6">
                </div>
            </div>
        </div>
    </div>

    </body>
    </html>
@endsection
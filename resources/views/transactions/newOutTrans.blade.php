@extends('layouts.base')

@section('title')
    New Out Transaction
@endsection

@section('script')

@endsection

@section('stylesheet')
@endsection

@section('body')
    <div class="mdui-container doc-container">
        <div class="mdui-row">
            <div class="mdui-col-xs-12">
                <div class="mdui-card">
                    <div class="mdui-card-header">
                        <div class="mdui-typo-display-2 mdui-text-center mdui-text-color-theme">
                            New Out Transaction
                        </div>
                    </div>
                    <div class="mdui-card-header-subtitle adjust_card_subtitle">
                        <div class="mdui-text-center">
                            便捷金融生活从此开启
                        </div>
                    </div>
                    <div class="mdui-card-content mdui-typo">
                        <form method="post" action="{{route('doPurchase')}}">
                            {{ csrf_field() }}
                            <div class="mdui-textfield mdui-textfield-floating-label">
                                <i class="mdui-icon material-icons adjust_mdui_icon">shopping_basket</i>
                                <label class="mdui-textfield-label">我的商品</label>
                                <input class="mdui-textfield-input" id="seller_item" type="text" required/>
                                <div class="mdui-textfield-error">我的商品不能为空</div>
                            </div>
                            <div class="mdui-textfield mdui-textfield-floating-label ">
                                <i class="mdui-icon material-icons adjust_mdui_icon">add</i>
                                <label class="mdui-textfield-label">我的数量</label>
                                <input class="mdui-textfield-input" id="seller_amount" type="number" required/>
                                <div class="mdui-textfield-error">我的数量不能为空</div>
                            </div>
                            <div class="mdui-textfield mdui-textfield-floating-label ">
                                <i class="mdui-icon material-icons adjust_mdui_icon">account_circle</i>
                                <label class="mdui-textfield-label">买方的ID</label>
                                <input class="mdui-textfield-input" id="buyer_id" type="number" required/>
                                <div class="mdui-textfield-error">买方的ID不能为空</div>
                            </div>
                            <div class="mdui-textfield mdui-textfield-floating-label ">
                                <i class="mdui-icon material-icons adjust_mdui_icon">add</i>
                                <label class="mdui-textfield-label">买方的数量</label>
                                <input class="mdui-textfield-input" id="buyer_amount" type="number" required/>
                                <div class="mdui-textfield-error">买方的数量不能为空</div>
                            </div>
                            <div class="mdui-textfield mdui-textfield-floating-label ">
                                <i class="mdui-icon material-icons adjust_mdui_icon">shopping_basket</i>
                                <label class="mdui-textfield-label">买方的商品</label>
                                <input class="mdui-textfield-input" id="buyer_item" type="text" required/>
                                <div class="mdui-textfield-error">商品名不能为空</div>
                            </div>
                            <input hidden id="transaction_type" value="sell">
                            <button data-no-instant class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme mdui-center">提交
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!--row-->
        </div>
    </div>
@endsection

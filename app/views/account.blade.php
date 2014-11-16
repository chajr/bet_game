@include('view.header')

<div class="row">
    <div class="center-block col-md-10">
        <h4>{{{$user}}} account</h4>
    </div>
</div>

@include('view.account_header')

<div class="row">
    <div class="center-block col-md-10">
        <div class="col-md-8 col-sm-12">@include('view.account_deposit')</div>
        <div class="col-md-4 col-sm-12">@include('view.account_wallets')</div>
    </div>
</div>
<div class="row">
    <div class="center-block col-md-10">
        @include('view.account_bet')
    </div>
</div>

@include('view.footer')

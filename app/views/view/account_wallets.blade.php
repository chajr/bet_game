<div class="panel panel-default">
    <div class="panel-heading">Your wallets</div>
    <div class="panel-body">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td>Total balance</td>
                    <td id="total_balance">{{{$total}}}</td>
                </tr>
                <tr>
                    <td>Real money</td>
                    <td id="real_money">{{{$real}}}</td>
                </tr>
                @foreach ($bonus as $info)
                <tr>
                    <td>{{$info['name']}}</td>
                    <td>{{$info['value']}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

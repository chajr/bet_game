@include('view.header')
<div class="panel panel-default" id="login_form">
    <div class="panel-heading">Logowanie</div>
    <form role="form" method="post" action="/authenticate">
        <p>
            <input type="hidden" name="login" value="yes">
        </p>
        <div class="form-group">
            <label for="username">Użytkownik:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="glyphicon glyphicon-user"></i>
                </div>
                <input type="email" placeholder="User email" name="email" id="username" class="form-control"/>
            </div>
        </div>

        <div class="form-group">
            <label for="password">Hasło:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="glyphicon glyphicon-lock"></i>
                </div>
                <input type="password" placeholder="Password" name="password" id="password" class="form-control"/>
            </div>
        </div>

        <p class="clear"></p>
        <button class="btn btn-primary" type="submit">Zaloguj</button>
    </form>
    <div class="clear"></div>
</div>
@include('view.footer')

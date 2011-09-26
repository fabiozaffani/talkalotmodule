{* Here we are embending the SDK Asynchornously *}

<div id="fb-root"></div>
<script type="text/javascript">
    var locProt = "//connect.facebook.net/{$lang}/all.js";
    {literal}
        window.fbAsyncInit = function() {
            FB.init({
                appId: {/literal}{$app_id}{literal}, 
                status: true, 
                cookie: true,
                xfbml: true
            });
            fbInitializedCallback()
        };
        (function() {
            var e = document.createElement('script'); 
            e.src = document.location.protocol + locProt;
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
        }());
        function fbInitializedCallback()
        {
            if (typeof fbInitializedLike == 'function')
                fbInitializedLike();
            if (typeof fbInitializedCreateAccount == 'function')
                fbInitializedCreateAccount();
        }
    {/literal}
</script>



<!DOCTYPE html>
<html>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        function updateUserStatus(){
            jQuery.ajax({
                url:'jQuery/updateOnlineStatus.php',
                success:function(){

                }
            });
        }

        updateUserStatus();
        setInterval(function(){
            updateUserStatus();
        },5000);
    </script>
</html>
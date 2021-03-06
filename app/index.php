<?php 
    include('template_top.php');
    include('env.php');
?>

<h1>Hello there.</h1>
<h2>Welcome to a secret website.</h2>
<h3>You now have the chance to share a secret. Anonymously. Leave it out into the open, into the world.</h3>
<h4>Why? For the entertainment of others? For your own peace of mind? That's up to you.</h4>
<h5>You can also choose to not share anything, and just view instead.</h5>
<h6>Whatever you choose, once you press continue you'll see all the secrets shared.</h6>
<p>(And hopefully not a collection of swear words and vandalism)</p>

<form action="secrets.php" method="POST">
    <label><input type="radio" name="share" value="1" required id="yesshare">I'd like to share a secret!</label><br>
    <label><input type="radio" name="share" value="0" required id="noshare">I'd like to not!</label><br>
    <br>
    <input type="text" placeholder="Type your secret here." name="secret" id="secret"
        pattern="<?php echo "$SECRET_ALLOWED_CHARS_HTML{1,$SECRET_MAX_LENGTH}" ?>" 
        title="a-Z, 0-9, spaces, decimals, commas, question- and exclamationmarks.">
    <br>
    <span>(Oh, only use letters, numbers, spaces, and .,?! )</span>
    <br>
    <span>And note that - if you choose to share - you can only share one secret, otherwise you'll overwrite your previous!</span>
    <br>
    <input type="submit" value="Continue">
</form>

<script>
    function EnableInput() {
        // If 1, share secret
        if (parseInt(this.value)) {
            document.getElementById("secret").removeAttribute("disabled");
        } else {
            // If 0, don't share, disabled input
            document.getElementById("secret").setAttribute("disabled", true);
        }
    }

    document.getElementById("yesshare").addEventListener("input", EnableInput);
    document.getElementById("noshare").addEventListener("input", EnableInput);

</script>

<?php include('template_bot.php'); ?>


<?php 
    include('template_top.php');
    include('env.php');
?>

<h1>Hello there.</h1>
<h2>Welcome to a secret website.</h2>
<h3>You now have the chance to share a secret. Anonymously. Leave it out into the open, into the world.</h3>
<h4>Why? For the entertainment of others? For your own peace of mind? That's up to you.</h4>
<h5>You can also choose to not share anything, and just view instead.</h5>
<h6>Whatever you choose, you'll hopefully get to view a collection of secrets.</h6>
<p>(And hopefully not a collection of swear words and vandalism)</p>

<form action="secrets.php" method="POST">
    <label><input type="radio" name="share" value="1" required>I'd like to share a secret!</label><br>
    <label><input type="radio" name="share" value="0" required>I'd like to not!</label><br>
    <br>
    <input type="text" placeholder="Type your secret here." name="secret"
        pattern="<?php echo $SECRET_ALLOWED_CHARS_HTML ?>" 
        maxlength="<?php echo $SECRET_MAX_LENGTH ?>">
    <br>
    <span>(Oh, only use letters, numbers, spaces, and .,?! )</span>
    <br>
    <input type="submit" value="Continue">
</form>

<?php include('template_bot.php'); ?>


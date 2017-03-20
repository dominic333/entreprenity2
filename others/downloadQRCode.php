<html>
<head><title>Download QRCode</title></head>
<body>
<form name="download" method="post" action="downloadQRCodeAction.php">
<input type="text" name="staff_id" value="<?php if (isset($_POST['staff_id'])) { echo $staff_id; } ?>" class="form-control" id="staff_id" required="">
<button name="submit"  type="submit">Generate</button>
</form>
</body>
</html>

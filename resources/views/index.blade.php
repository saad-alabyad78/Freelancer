<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Send Form Request</title>
</head>
<body>

<form action="http://127.0.0.1:8000/api/company/store" method="POST">
  <label for="name">الاسم:</label>
  <input type="text" id="name" name="name" required>

  <label for="email">البريد الإلكتروني:</label>
  <input type="email" id="email" name="email" required>

  <input type="submit" value="إرسال">
</form>

</body>
</html>

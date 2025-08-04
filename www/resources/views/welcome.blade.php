<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@3/swagger-ui.css">
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist@3/swagger-ui-bundle.js"></script>
<script>
    window.onload = () => {
        SwaggerUIBundle({
            url: 'http://localhost/swagger/gen/',
            dom_id: '#swagger-ui',
        });
    };
</script>
</body>
</html>

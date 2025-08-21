<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Swagger UI</title>
  <link rel="stylesheet" type="text/css" href="./swagger/swagger-ui.css" />
  <link rel="stylesheet" type="text/css" href="./swagger/index.css" />
  <link rel="icon" type="image/png" href="./swagger/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="./swagger/favicon-16x16.png" sizes="16x16" />
</head>

<body>
  <div id="swagger-ui"></div>
  <script src="./swagger/swagger-ui-bundle.js" charset="UTF-8"> </script>
  <script src="./swagger/swagger-ui-standalone-preset.js" charset="UTF-8"> </script>

  <script>
    const ui = SwaggerUIBundle({
      url: "./swagger/spec.json",
      dom_id: '#swagger-ui',
      deepLinking: true,
      presets: [
        SwaggerUIBundle.presets.apis,
        SwaggerUIStandalonePreset
      ],
      plugins: [
        SwaggerUIBundle.plugins.DownloadUrl
      ],
      layout: "StandaloneLayout",
      onComplete: () => {
        const appHost = "{{ config('app.url') }}/api";
        const spec = ui.specSelectors.specJson().toJS();

        spec.servers = [{ url: appHost }];

        ui.specActions.updateJsonSpec(spec);
      }
    });
  </script>
</body>

</html>
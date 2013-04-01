# Require any additional compass plugins here.
http_path = "/"
css_dir = "assets/css"
sass_dir = "assets/scss"
images_dir = "assets/img"
javascripts_dir = "assets/js"
fonts_dir = "assets/font"

relative_assets = true
preferred_syntax = :scss

environment = :development

output_style = (environment == :production) ? :compressed : :expanded

line_comments = (environment == :production) ? false : true

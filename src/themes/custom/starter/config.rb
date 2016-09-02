# Compass configuration file
css_dir = "css"
sass_dir = "sass"
extensions_dir = "sass-extensions"
images_dir = "img"
javascripts_dir = "js"
output_style = (environment == :production) ? :compressed : :expanded
relative_assets = true
sass_options = (environment == :production) ? {} : {:sourcemap => true}

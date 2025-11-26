# Curatescape SEO

Adds enhanced `<meta>` tags for improved search engine optimization and social media sharing. Recommended for all projects using the core [Curatescape plugin](https://omeka.org/classic/plugins/Curatescape/). Compatible with all Omeka Classic themes. Supports items, collections, Exhibit Builder content, Simple Pages content, and Curatescape stories and tours.

## FAQ

### Which `<meta>` tags are supported and how are they populated?

See example output below. This content will be added to your site/theme header.

```
<meta property="og:site_name" content="[your site title]">
<meta property="og:type" content="website" />
<meta property="og:url" content="[your content url]" />
<meta property="og:title" content="[your content title]" />
<meta property="og:description" content="[your content text]" />
<meta property="og:image" content="[your fullsize image url]" />
<meta property="twitter:card" content="summary_large_image" />
<meta property="twitter:url" content="[your content url]" />
<meta property="twitter:title" content="[your content title]" />
<meta property="twitter:description" content="[your content text]" />
<meta property="twitter:image" content="[your fullsize image url]" />
```

All `image` tags will use an image associated with the page content. If an image is not available, the configured `Meta Image` plugin setting will be used.

All `description` tags will use text associated with the page content. If text is not available, the `Site Description` site setting will be used.

### How can I control which image is used to represent the item?

The first image in the item files list will be used in search engine and social media previews. You can drag and drop to move an alternate file to the top of the list. The `record_image` for collections and exhibits will be used when available. For items that do not include images – and for pages containing no items or multiple items, as well as for generated pages like the homepage – the configured `Meta Image` plugin setting will be used.

### Is it possible to add per-item configurations?

In the future, we may add additional options for customizing item-specific `<meta>` tags. For reference, see the [Item SEO plugin](https://github.com/ebellempire/ItemSEO), which adds an option for `<link rel="canonical">` in the item header. That plugin will likely be superseded by this one going forward.

### How can I _upload_ an actual file for use as the default custom meta image?

The Curatescape SEO plugin includes an option to define a default meta image for search engine and social media previews. This option accepts a URL path to an image file. The image should be on the same server as the website, so you may need to upload the file using an FTP client or web-based File Manager.

As a convenient alternative, theme developers may add the following option to their theme's `config.ini` file so that the image file can be easily set and updated in theme settings.

```
curatescapeseo_meta_image.type = "file"
curatescapeseo_meta_image.options.label = "Meta Image"
curatescapeseo_meta_image.options.description = "Upload a custom meta image file for use when there is not a content-related image available (for example, on the homepage and browse pages). Recommended dimensions: 1200px × 630px (1.91:1)."
curatescapeseo_meta_image.options.validators.count.validator = "Count"
curatescapeseo_meta_image.options.validators.count.options.max = "1"
```

### What kind of images should I use? How big should the images be?

The current recommendation for `<meta>` images is to use a PNG or JPG file that is about 1200px × 630px (1.91:1 aspect ratio). This specific size is just a recommendation for optimizing display across popular platforms and not an actual requirement. That said, to ensure that your content images aren't too small to be used, consider updating your site settings and [regenerating existing thumbnails](https://omeka.org/classic/plugins/DerivativeImages/) so that fullsize files are _at least_ 1000px in size.

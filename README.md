# GreenGrowth Impact Showcase

A high-performance, accessible project showcase block for WordPress that displays GreenGrowth's reforestation and sustainability projects in a filterable, responsive grid.

Built with the **WordPress Interactivity API** for optimal performance, SEO, and sustainability.

## Features

- **Interactive Filtering**: Filter projects by service area (Reforestation, Carbon Capture, Sustainable Farming) with instant client-side updates
- **Server-Side Rendering**: Initial HTML rendered on the server for fast page loads and SEO benefits
- **Responsive Design**: 3-column grid on desktop, 2-column on tablet, 1-column on mobile
- **Fully Accessible**: WCAG AA compliant with keyboard navigation, screen reader support, and ARIA landmarks
- **Low Carbon Design**: Optimized for minimal data transfer and processing power
- **Translation Ready**: Fully internationalized with WordPress i18n functions
- **Rebuild Ready**: Self-contained plugin with no theme dependencies

## Requirements

- WordPress 6.9 or higher
- PHP 7.4 or higher
- Modern browser (Chrome, Firefox, Safari, Edge - last 2 versions)

## Installation

### Method 1: Manual Installation

1. Download or clone this repository
2. Upload the `greengrowth-impact-showcase` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. 30 sample projects will be automatically created on first activation

### Method 2: Development Installation

```bash
# Clone the repository
cd wp-content/plugins/
git clone [repository-url] greengrowth-impact-showcase

# Install dependencies
cd greengrowth-impact-showcase
npm install

# Build assets
npm run build

# Activate via WordPress admin or WP-CLI
wp plugin activate greengrowth-impact-showcase
```

## Usage

### Adding the Block

1. Edit any page or post in the WordPress Block Editor
2. Click the '+' icon to add a new block
3. Search for "Impact Showcase"
4. Insert the block into your content
5. Publish or update the page

The block will automatically display all published projects with filter buttons for each service area.

### Managing Projects

#### Add a New Project

1. Navigate to **Projects > Add New** in the WordPress admin
2. Enter the project title, description, and excerpt
3. Set a **Featured Image** (recommended: 800x600px, 4:3 aspect ratio)
4. Select one or more **Service Areas** (Reforestation, Carbon Capture, Sustainable Farming)
5. Publish the project

#### Edit Service Areas

1. Navigate to **Projects > Service Areas**
2. Add, edit, or delete service area terms
3. New service areas will automatically appear in the filter buttons

#### Bulk Import Projects

For importing multiple projects, use a WordPress import plugin like:
- WP All Import
- CSV Importer
- WordPress Importer

## Block Attributes & Customization

### Alignment

The Impact Showcase block supports WordPress alignment options:

- **None**: Default content width
- **Wide**: Expands to wide width (if theme supports it)
- **Full**: Full-width layout

### Spacing

The block supports WordPress spacing controls:

- **Margin**: Adjust outer spacing around the block
- **Padding**: Adjust inner spacing within the block

### Custom Styling

To customize the block appearance, add CSS to your theme:

```css
/* Custom colors */
.wp-block-greengrowth-impact-showcase {
  --gg-color-primary: #your-color;
  --gg-color-primary-hover: #your-hover-color;
}

/* Custom spacing */
.gg-projects-grid {
  gap: 3rem; /* Increase gap between cards */
}

/* Custom card styling */
.gg-project-card {
  border-radius: 12px; /* More rounded corners */
}
```

## Performance Optimization

### Image Optimization

For best performance, optimize your project images:

1. **Use WebP format** when possible (smaller file size)
2. **Recommended size**: 800x600px (matches the 4:3 aspect ratio)
3. **Compress images** with tools like:
   - TinyPNG
   - ImageOptim
   - Squoosh

### Caching

The block works with popular caching plugins:

- WP Super Cache
- W3 Total Cache
- WP Rocket
- LiteSpeed Cache

No special configuration needed - server-side rendering ensures cached pages are fully functional.

### Carbon Footprint

Test your page's carbon footprint:

- [Website Carbon Calculator](https://www.websitecarbon.com/)
- [Ecograder](https://ecograder.com/)
- Target: Cleaner than 70% of pages tested

## Accessibility

### Keyboard Navigation

- **Tab**: Navigate between filter buttons and project links
- **Enter/Space**: Activate filter buttons
- **Shift+Tab**: Navigate backwards

### Screen Readers

- Filter buttons announce as "Filter projects by service area"
- Active filter state announced (e.g., "Showing 10 projects in Reforestation")
- Each project card announces title and excerpt

### WCAG Compliance

- **WCAG AA**: All color contrasts meet 4.5:1 ratio
- **Keyboard Accessible**: All functionality available via keyboard
- **Screen Reader Compatible**: Tested with VoiceOver and NVDA
- **Focus Indicators**: Clear visual focus states
- **Reduced Motion**: Respects `prefers-reduced-motion` setting

## Development

### File Structure

```
greengrowth-impact-showcase/
├── greengrowth-impact-showcase.php    # Main plugin file
├── package.json                        # Build configuration
├── webpack.config.js                   # Webpack configuration
├── .gitignore                          # Git ignore rules
├── README.md                           # This file
├── src/
│   ├── post-types/
│   │   └── project.php                 # CPT and taxonomy registration
│   └── blocks/
│       └── impact-showcase/
│           ├── block.json              # Block metadata
│           ├── index.js                # Editor registration
│           ├── edit.js                 # Editor component
│           ├── render.php              # Server-side rendering
│           ├── view.js                 # Interactivity API store
│           └── style.scss              # Block styles
└── build/                              # Compiled assets (generated)
    └── blocks/
        └── impact-showcase/
            ├── index.js
            ├── view.js
            ├── style-index.css
            └── style-index-rtl.css
```

### Build Scripts

```bash
# Development mode (watch for changes)
npm run start

# Production build
npm run build

# Lint JavaScript
npm run lint:js

# Lint CSS
npm run lint:css

# Format code
npm run format

# Create installable ZIP
npm run plugin-zip
```

### Modifying the Block

#### Change Filter Button Labels

Edit [`src/blocks/impact-showcase/render.php`](src/blocks/impact-showcase/render.php):

```php
<button>
  <?php esc_html_e( 'Your Custom Label', 'greengrowth-impact-showcase' ); ?>
</button>
```

#### Add Custom State Properties

Edit [`src/blocks/impact-showcase/view.js`](src/blocks/impact-showcase/view.js):

```javascript
state: {
  get yourCustomState() {
    const context = getContext();
    return context.yourCustomValue;
  }
}
```

#### Modify Grid Layout

Edit [`src/blocks/impact-showcase/style.scss`](src/blocks/impact-showcase/style.scss):

```scss
.gg-projects-grid {
  grid-template-columns: repeat(4, 1fr); // 4 columns instead of 3
}
```

## Internationalization

### Translation Ready

The plugin is fully translation-ready with text domain: `greengrowth-impact-showcase`

### Create Translations

1. Use [Poedit](https://poedit.net/) or [Loco Translate](https://wordpress.org/plugins/loco-translate/)
2. Create a `.po` file for your language (e.g., `greengrowth-impact-showcase-es_ES.po`)
3. Translate the strings
4. Generate a `.mo` file
5. Place files in `wp-content/languages/plugins/`

### Translation Strings

All user-facing strings use WordPress i18n functions:

- `__()` - Returns translated text
- `_e()` - Echoes translated text
- `_x()` - Returns translated text with context
- `esc_html__()` - Returns escaped translated text
- `esc_html_e()` - Echoes escaped translated text

## Future Enhancements

The plugin architecture supports these planned features:

### Multilingual Support

- Compatible with WPML, Polylang, and other translation plugins
- Ready for multi-language project filtering

### Map View

The data structure includes provisions for location data:

```php
// Add custom fields for latitude/longitude
add_post_meta($post_id, '_gg_latitude', '40.7128');
add_post_meta($post_id, '_gg_longitude', '-74.0060');
```

### Additional Filters

Add more filtering options:

- Date range (projects by year)
- Location (projects by country/region)
- Impact metrics (projects by trees planted, carbon offset, etc.)

## Troubleshooting

### Block Doesn't Appear in Editor

1. Clear WordPress cache (if using a caching plugin)
2. Rebuild assets: `npm run build`
3. Check browser console for JavaScript errors
4. Ensure WordPress 6.9+ is installed

### Projects Don't Filter

1. Check browser console for JavaScript errors
2. Verify the Interactivity API is loaded (check page source for `wp-interactivity`)
3. Clear browser cache
4. Test in a different browser

### Styling Issues

1. Clear browser cache
2. Rebuild assets: `npm run build`
3. Check for theme CSS conflicts (use browser DevTools)
4. Verify `style-index.css` is loaded in page source

### Sample Projects Not Created

1. Deactivate and reactivate the plugin
2. Or manually run: `wp eval "gg_create_sample_projects();"`
3. Check WordPress debug log for errors (`WP_DEBUG_LOG`)

## Support & Contribution

### Bug Reports

Please report bugs via [GitHub Issues](your-repo-url/issues) with:

- WordPress version
- PHP version
- Theme name
- Steps to reproduce
- Expected vs actual behavior
- Browser console errors (if any)

### Feature Requests

Submit feature requests via [GitHub Issues](your-repo-url/issues) with the `enhancement` label.

### Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

GPL v2 or later

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

## Credits

Built for **GreenGrowth** - A reforestation NGO committed to sustainable environmental practices.

**Technology Stack:**
- WordPress Interactivity API
- WordPress Block Editor (Gutenberg)
- React (via @wordpress/element)
- Sass/SCSS
- @wordpress/scripts

**Sustainable Design Principles:**
- Server-side rendering for reduced JavaScript execution
- Lazy-loaded images
- Optimized asset delivery
- Minimal HTTP requests
- Low-carbon web design practices

---

**Version:** 1.0.0
**Author:** GreenGrowth
**Requires at least:** WordPress 6.9
**Tested up to:** WordPress 6.9
**Requires PHP:** 7.4
**License:** GPL-2.0-or-later

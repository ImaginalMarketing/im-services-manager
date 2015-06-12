# IM Services Manager Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

### To Do:
- Prevent plugins like Yoast SEO from infiltrating the post type
- Display shortcode in category list || button in editor like grav forms
- 5 Levels >>> INFINITE LEVELS
- Github!

_ _ _


## [1.0.4] - 2015-06-12
#### Added
- Updated table header to allow category description to span full width of table.



## [1.0.3] - 2015-06-08
#### Added
- Rewrote services table output to fix duplicate line item issues. (converted from a bunch of if/else statements to a small array handler)



## [1.0.0] - 2015-05-04
#### Added
- Integrated Taxonomy Meta plugin because I apparently can't write my own
- Created setting to hide table header if desired

#### Changed
- Shortcode output markup. Cleaner != better, but it certainly helps
- Rewrote much of the output logic to handle more possible combinations of user input

#### Fixed
- Location is no longer required for shortcode to function
- Post_updated_messages no longer breaks on all post types
- Improved column detection when service line items have varying price levels

## [0.9] - 2015-01-xx
#### Added
- The plugin. Hello world!
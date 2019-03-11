## Better Blockquotes

Better Blockquotes provides an way to easily add citations to quotes using the WordPress editor. Simply click on the blockquote button, and a pop-up will provide fields for quote, citation and citation link. Developers can extend the plugin to support various blockquote classes.

![A screenshot of the Better Blockquotes pop-up window.](https://github.com/devinsays/better-blockquotes/raw/master/assets/screenshot-1.jpg)

### Requirements

* WordPress 4.3 or higher

### Frequently Asked Questions

#### What does the blockquote markup look like?

```
<blockquote>
This is the quote.
<cite><a href="#">Citation</a></cite>
</blockquote>
```

#### Why doesn't my blockquote look good when displayed?

This plugin doesn't add any styling for blockquotes. Contact the author of your theme to make sure they support blockquote markup.

#### Blockquote Classes/Styles

Developers can optionally add style options to the pop-up. Any selected styles will apply a class directly to the `<blockquote>` element.

```
function prefix_blockquote_classes() {
	$options = array(
		'class-1' => __( 'Style One', 'better-blockquotes' ),
		'class-2' => __( 'Style Two', 'better-blockquotes' ),
	);
	return $options;
}
add_filter( 'betterblockquotes_classes', 'prefix_blockquote_classes' );
```

### Change Log

#### Version 1.0.0

* Public release
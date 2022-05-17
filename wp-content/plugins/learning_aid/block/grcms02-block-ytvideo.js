wp.blocks.registerBlockType("learning-aid/grcms02-block-ytvideo", {
    title: "YT Video",
    description: "This is a custom block for youtube videos",
    category: "widgets",
    icon: "youtube",
    attributes: {
        link: {
            type: "string",
        },
    },

    edit: function(props) {
        function updateLink(event) {
            props.setAttributes({
                link: event.target.value,
            });
        }

        return wp.element.createElement(
            "div", {
                className: "grcms02-block-ytvideo-edit-container",
            },
            wp.element.createElement(
                "div", {
                    className: "grcms02-block-ytvideo-edit-title",
                },
                "Insert Youtube Link"
            ),
            wp.element.createElement("input", {
                className: "grcms02-block-ytvideo-edit-input",
                type: "text",
                value: props.attributes.link,
                onChange: updateLink,
            })
        );
    },

    save: function(props) {

        var yt_iframe_embed = '[youtube-embed-url]' + props.attributes.link + '[/youtube-embed-url]';
        return wp.element.createElement(
            "div", {
                className: 'grcms02-block-ytvideo-save-div',
            },
            yt_iframe_embed
        );
    },
});
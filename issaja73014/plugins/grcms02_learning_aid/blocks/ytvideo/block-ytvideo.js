wp.blocks.registerBlockType('learning-aid/block-ytvideo', {
    title: "YT Video",
    description: "This is a custom block for youtube videos",
    category: "widgets",
    icon: "youtube",
    attributes: {
        title: {
            type: 'string',
        },
        link: {
            type: "string",
        },
    },

    edit: function(props) {
        function updateTitle(event) {
            props.setAttributes({
                title: event.target.value,
            });
        }

        function updateLink(event) {
            props.setAttributes({
                link: event.target.value,
            });
        }

        return wp.element.createElement(
            "div", {
                className: "block-ytvideo-edit-container",
                tabindex: "-1",
            },
            wp.element.createElement(
                "div", {
                    className: "block-ytvideo-edit-label",
                },
                "Insert Title"
            ),
            wp.element.createElement("input", {
                className: "block-ytvideo-edit-input",
                placeholder: "Title",
                type: "text",
                value: props.attributes.title,
                onChange: updateTitle,
            }),
            wp.element.createElement(
                "div", {
                    className: "block-ytvideo-edit-label",
                },
                "Insert YouTube URL"
            ),
            wp.element.createElement("input", {
                className: "block-ytvideo-edit-input",
                placeholder: "Example: https://www.youtube.com/watch?v=abcdefghijk",
                type: "text",
                value: props.attributes.link,
                onChange: updateLink,
            }),
        );
    },

    save: function(props) {

        var yt_iframe_embed = '[youtube-embed-url]' + props.attributes.link + '[/youtube-embed-url]';

        return wp.element.createElement(
            "div", {
                className: "block-ytvideo-save-container",
            },
            wp.element.createElement(
                "div", {
                    className: "block-ytvideo-save-label",
                },
                props.attributes.title,
            ),
            wp.element.createElement(
                "div", {
                    className: "block-ytvideo-save-iframe",
                },
                yt_iframe_embed,
            ),
        );


    },
});
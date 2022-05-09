wp.blocks.registerBlockType('learning-aid/grcms02-block-ytvideo', {

    title: 'YT Video',
    description: 'This is a custom block for youtube videos',
    category: 'widgets',
    icon: 'youtube',
    attributes: {
        link: {
            type: 'string'
        },
    },

    edit: function(props) {
        function updateLink(event) {
            props.setAttributes({
                link: event.target.value
            })
        }

        return wp.element.createElement(
            "div", {
                className: 'grcms02-block-ytvideo-edit-container',
            },
            wp.element.createElement(
                "div", {
                    className: 'grcms02-block-ytvideo-edit-title',
                },
                "Insert Youtube Link"
            ),
            wp.element.createElement(
                "input", {
                    className: 'grcms02-block-ytvideo-edit-input',
                    type: "text",
                    value: props.attributes.link,
                    onChange: updateLink,
                }
            ),
        );
    },

    save: function(props) {
        function youtubeParser(url) {
            var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
            var match = url.match(regExp);
            return (match && match[7].length == 11) ? match[7] : false;
        }

        var url = props.attributes.link;
        var youtube_id = youtubeParser(url);

        if (youtube_id) {
            return wp.element.createElement(
                "iframe", {
                    className: "grcms02-block-ytvideo-save-iframe",
                    src: "https://www.youtube-nocookie.com/embed/" + youtube_id,
                }
            );
        } else {
            return wp.element.createElement(
                "div", {
                    className: "grcms02-block-ytvideo-save-error",
                },
                "Error: " + url + " is not a valid Youtube link"
            );
        }
    }
});
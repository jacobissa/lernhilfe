wp.blocks.registerBlockType('learning-aid/grcms02-block-flashcard', {

    title: 'Flashcard',
    description: 'This is a custom block for flashcard',
    category: 'widgets',
    icon: 'index-card',
    attributes: {
        front: {
            type: 'string'
        },
        back: {
            type: 'string'
        },
    },

    edit: function(props) {
        function updateFront(event) {
            props.setAttributes({
                front: event.target.value
            })
        }

        function updateBack(event) {
            props.setAttributes({
                back: event.target.value
            })
        }

        return wp.element.createElement(
            "div", {
                className: 'grcms02-block-flashcard-edit-container',
            },
            wp.element.createElement(
                "div", {
                    className: 'grcms02-block-flashcard-edit-front-title',
                },
                "Write the Front:"
            ),
            wp.element.createElement(
                "input", {
                    className: 'grcms02-block-flashcard-edit-front-input',
                    type: "text",
                    value: props.attributes.front,
                    onChange: updateFront,
                }
            ),
            wp.element.createElement(
                "div", {
                    className: 'grcms02-block-flashcard-edit-back-title',
                },
                "Write the Back:"
            ),
            wp.element.createElement(
                "input", {
                    className: 'grcms02-block-flashcard-edit-back-input',
                    type: "text",
                    value: props.attributes.back,
                    onChange: updateBack,
                }
            ),
        );
    },

    save: function(props) {
        return wp.element.createElement(
            "div", {
                className: "grcms02-block-flashcard-save-container",
            },
            wp.element.createElement(
                "div", {
                    className: "grcms02-block-flashcard-save-front",
                },
                props.attributes.front,
            ),
            wp.element.createElement(
                "div", {
                    className: "grcms02-block-flashcard-save-back",
                },
                props.attributes.back,
            ),
        );

    }
});
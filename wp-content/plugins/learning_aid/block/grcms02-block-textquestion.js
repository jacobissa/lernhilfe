wp.blocks.registerBlockType('learning-aid/grcms02-block-textquestion', {

    title: 'TextQuestion',
    description: 'This is a custom block for textquestion',
    category: 'widgets',
    icon: 'index-card',
    attributes: {
        question: {
            type: 'string'
        },
    },

    edit: function(props) {
        function updateQuestion(event) {
            props.setAttributes({
                question: event.target.value
            })
        }

        return wp.element.createElement(
            "div", {
                className: 'grcms02-block-textquestion-edit-container',
            },
            wp.element.createElement(
                "div", {
                    className: 'grcms02-block-textquestion-edit-question-title',
                },
                "Write the Question:"
            ),
            wp.element.createElement(
                "input", {
                    className: 'grcms02-block-textquestion-edit-question-input',
                    type: "text",
                    value: props.attributes.question,
                    onChange: updateQuestion,
                }
            ),
        );
    },

    save: function(props) {
        return wp.element.createElement(
            "div", {
                className: "grcms02-block-textquestion-save-container",
            },
            wp.element.createElement(
                "div", {
                    className: "grcms02-block-textquestion-save-question",
                },
                props.attributes.question,
            ),
            wp.element.createElement(
                "textarea", {
                    className: "grcms02-block-textquestion-save-answer",
                },
                '',
            ),
        );

    }
});
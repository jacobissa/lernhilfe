wp.blocks.registerBlockType('learning-aid/grcms02-block-flashcard', {

    title: 'Flashcard',
    description: 'This is a custom block for flashcard',
    category: 'widgets',
    icon: 'index-card',
    attributes: {
        question: {
            type: 'string'
        },
        answer: {
            type: 'string'
        },
    },

    edit: function(props) {
        function updateQuestion(event) {
            props.setAttributes({
                question: event.target.value
            })
        }

        function updateAnswer(event) {
            props.setAttributes({
                answer: event.target.value
            })
        }

        return wp.element.createElement(
            "div", {
                className: 'grcms02-block-flashcard-edit-container',
            },
            wp.element.createElement(
                "div", {
                    className: 'grcms02-block-flashcard-edit-question-title',
                },
                "Write the question:"
            ),
            wp.element.createElement(
                "input", {
                    className: 'grcms02-block-flashcard-edit-question-input',
                    type: "text",
                    value: props.attributes.question,
                    onChange: updateQuestion,
                }
            ),
            wp.element.createElement(
                "div", {
                    className: 'grcms02-block-flashcard-edit-answer-title',
                },
                "Write the answer:"
            ),
            wp.element.createElement(
                "input", {
                    className: 'grcms02-block-flashcard-edit-answer-input',
                    type: "text",
                    value: props.attributes.answer,
                    onChange: updateAnswer,
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
                    className: "grcms02-block-flashcard-save-question",
                },
                props.attributes.question,
            ),
            wp.element.createElement(
                "div", {
                    className: "grcms02-block-flashcard-save-answer",
                },
                props.attributes.answer,
            ),
        );

    }
});
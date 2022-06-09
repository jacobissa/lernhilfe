wp.blocks.registerBlockType('learning-aid/block-textquestion', {

    title: 'TextQuestion',
    description: 'This is a custom block for text questions',
    category: 'widgets',
    icon: 'index-card',
    attributes: {
        blockid: {
            type: 'string',
        },
        question: {
            type: 'string'
        },
        solution: {
            type: 'string',
        },
    },

    edit: function(props) {

        function updateQuestion(event) {
            props.setAttributes({
                question: event.target.value
            });
            props.setAttributes({
                blockid: props.clientId
            })
        }

        function updateSolution(event) {
            props.setAttributes({
                solution: event.target.value
            });
            props.setAttributes({
                blockid: props.clientId
            })
        }

        return wp.element.createElement(
            "div", {
                className: 'block-textquestion-edit-container',
                tabindex: "-1",
            },
            wp.element.createElement(
                "div", {
                    className: 'block-textquestion-edit-label',
                },
                "Insert the Question:"
            ),
            wp.element.createElement(
                "input", {
                    className: 'block-textquestion-edit-input',
                    placeholder: "???",
                    type: "text",
                    value: props.attributes.question,
                    onChange: updateQuestion,
                }
            ),
            wp.element.createElement(
                "div", {
                    className: 'block-textquestion-edit-label',
                },
                "Insert the Solution:"
            ),
            wp.element.createElement(
                "textarea", {
                    className: 'block-textquestion-edit-input',
                    type: "text",
                    placeholder: "Enter keywords of the solution (separated by semicolon)",
                    value: props.attributes.solution,
                    onChange: updateSolution,
                }
            ),
        );
    },


    save: function(props) {

        return wp.element.createElement(
            "div", {
                className: "block-textquestion-save-container",
            },
            wp.element.createElement(
                "label", {
                    className: "block-textquestion-save-label",
                },
                props.attributes.question,
            ),
            wp.element.createElement(
                "textarea", {
                    className: "block-textquestion-save-input",
                    id: 'answer_' + props.attributes.blockid,
                    name: 'answer_' + props.attributes.blockid,
                    placeholder: ".....",
                },
                null,
            ),
            wp.element.createElement(
                "input", {
                    id: 'question_' + props.attributes.blockid,
                    name: 'question_' + props.attributes.blockid,
                    type: 'hidden',
                    value: props.attributes.question,
                },
                null,
            ),
            wp.element.createElement(
                "input", {
                    id: 'solution_' + props.attributes.blockid,
                    name: 'solution_' + props.attributes.blockid,
                    type: 'hidden',
                    value: props.attributes.solution,
                },
                null,
            ),
        );

    }
});
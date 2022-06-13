wp.blocks.registerBlockType('learning-aid/block-multiple-choice', {
    title: 'Multiple Choice Question',
    description: 'Custom block for multiple choice questions',
    category: 'widgets',
    icon: 'editor-ul',
    attributes: {
        title: {
            type: 'string'
        }, answers: {
            type: 'string'
        }, correct: {
            type: 'string'
        }, hint: {
            type: 'string'
        }
    },
    edit: function (props) {
        function updateTitle(event) {
            props.setAttributes({
                title: event.target.value,
            });
        }

        function updateAnswers(event) {
            props.setAttributes({
                answers: event.target.value,
            });
        }

        function updateCorrect(event) {
            props.setAttributes({
                correct: event.target.value,
            });
        }

        function updateHint(event) {
            props.setAttributes({
                hint: event.target.value,
            });
        }

        return wp.element.createElement('div', {
                className: 'block-multiple-choice-edit-container'
            },

            wp.element.createElement('div', {
                className: 'block-multiple-choice-edit-label'
            }, 'Write the title of the question'), wp.element.createElement('input', {
                className: 'block-multiple-choice-edit-input',
                placeholder: 'Question?',
                type: 'text',
                value: props.attributes.title,
                onChange: updateTitle
            }),

            wp.element.createElement('div', {
                className: 'block-multiple-choice-edit-label'
            }, 'Write the answers'), wp.element.createElement('input', {
                className: 'block-multiple-choice-edit-input',
                placeholder: 'Answers (separated with a semicolon). For example: One;Two;Three;Four',
                type: 'text',
                value: props.attributes.answers,
                onChange: updateAnswers
            }),

            wp.element.createElement('div', {
                className: 'block-multiple-choice-edit-label'
            }, 'State the correct answer'), wp.element.createElement('input', {
                className: 'block-multiple-choice-edit-input',
                placeholder: 'Correct answer',
                type: 'text',
                value: props.attributes.correct,
                onChange: updateCorrect
            }),

            wp.element.createElement('div', {
                className: 'block-multiple-choice-edit-label'
            }, 'Give a hint (optional)'), wp.element.createElement('input', {
                className: 'block-multiple-choice-edit-input',
                placeholder: 'Hint which will be shown in the correction',
                type: 'text',
                value: props.attributes.hint,
                onChange: updateHint
            }));
    },
    save: function (props) {
        // [multiple_choice_question title="" answers=";" correct="" (optional)hint=""]
        let shortcode = '[multiple_choice_question title="' + props.attributes.title
            + '" answers="' + props.attributes.answers
            + '" correct="' + props.attributes.correct
            + '" hint="' + props.attributes.hint + '"]';
        return shortcode;
    }
});
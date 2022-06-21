const {__} = wp.i18n;

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
            }, __('Write the title of the question', 'multiple-choice')), wp.element.createElement('input', {
                className: 'block-multiple-choice-edit-input',
                placeholder: __('Question?', 'multiple-choice'),
                type: 'text',
                value: props.attributes.title,
                onChange: updateTitle
            }),

            wp.element.createElement('div', {
                className: 'block-multiple-choice-edit-label'
            }, __('Write the answers', 'multiple-choice')), wp.element.createElement('input', {
                className: 'block-multiple-choice-edit-input',
                placeholder: __('Answers (separated with a semicolon). For example: One;Two;Three;Four', 'multiple-choice'),
                type: 'text',
                value: props.attributes.answers,
                onChange: updateAnswers
            }),

            wp.element.createElement('div', {
                className: 'block-multiple-choice-edit-label'
            }, __('State the correct answer', 'multiple-choice')), wp.element.createElement('input', {
                className: 'block-multiple-choice-edit-input',
                placeholder: __('Correct answer. For example: Two', 'multiple-choice'),
                type: 'text',
                value: props.attributes.correct,
                onChange: updateCorrect
            }),

            wp.element.createElement('div', {
                className: 'block-multiple-choice-edit-label'
            }, __('Give a hint (optional)', 'multiple-choice')), wp.element.createElement('input', {
                className: 'block-multiple-choice-edit-input',
                placeholder: __('Hint which will be shown in the correction', 'multiple-choice'),
                type: 'text',
                value: props.attributes.hint,
                onChange: updateHint
            }));
    },
    save: function (props) {
        // [multiple_choice_question title="" answers=";" correct="" (optional)hint=""]
        let title = props.attributes.title === undefined ? '' : props.attributes.title;
        let answers = props.attributes.answers === undefined ? '' : props.attributes.answers;
        let correct = props.attributes.correct === undefined ? '' : props.attributes.correct;
        let hint = props.attributes.hint === undefined ? '' : props.attributes.hint;
        let shortcode = '[multiple_choice_question title="' + title
            + '" answers="' + answers
            + '" correct="' + correct
            + '" hint="' + hint + '"]';
        return shortcode;
    }
});
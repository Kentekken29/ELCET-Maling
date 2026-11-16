document.querySelectorAll('[data-inline-fee-form]').forEach((form) => {
    const feeDisplay = form.querySelector('[data-fee-display]');
    const feeEditor = form.querySelector('[data-fee-editor]');
    const feeInput = form.querySelector('[data-fee-input]');
    const editButton = form.querySelector('[data-fee-edit]');
    const feeActions = form.querySelector('[data-fee-actions]');
    const cancelButton = form.querySelector('[data-fee-cancel]');
    const originalFee = feeInput.value;

    editButton.addEventListener('click', () => {
        feeDisplay.hidden = true;
        feeEditor.hidden = false;
        editButton.hidden = true;
        editButton.setAttribute('aria-expanded', 'true');
        feeActions.hidden = false;
        feeInput.focus();
        feeInput.select();
    });

    cancelButton.addEventListener('click', () => {
        feeInput.value = originalFee;
        feeDisplay.hidden = false;
        feeEditor.hidden = true;
        editButton.hidden = false;
        editButton.setAttribute('aria-expanded', 'false');
        feeActions.hidden = true;
        editButton.focus();
    });
});

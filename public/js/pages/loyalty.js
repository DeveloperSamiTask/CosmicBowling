$(function () {
    const page = document.getElementById('loyaltyPage');
    if (!page) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const purchaseModal = new bootstrap.Modal(document.getElementById('loyaltyPurchaseModal'));
    let searchedDocumentNumber = '';
    let searchedDocumentType = '01';
    let currentClient = null;

    $('#loyaltyDocumentNumber').on('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9-]/g, '').slice(0, 20);
    });

    $('#searchLoyaltyClientForm').on('submit', async function (event) {
        event.preventDefault();
        searchedDocumentType = $('#loyaltyDocumentType').val();
        searchedDocumentNumber = $('#loyaltyDocumentNumber').val().trim().toUpperCase();

        if (!isValidDocument(searchedDocumentType, searchedDocumentNumber)) {
            return showMessage('warning', documentValidationMessage(searchedDocumentType));
        }

        setSearching(true);
        try {
            const response = await fetch(`${page.dataset.searchUrl}/${encodeURIComponent(searchedDocumentNumber)}?document_id=${encodeURIComponent(searchedDocumentType)}`, { headers: { Accept: 'application/json' } });
            const data = await response.json();
            if (!response.ok) throw data;
            currentClient = data.found ? data.client : null;
            renderSearchResult(data);
        } catch (error) {
            showMessage('error', error.message || 'No se pudo buscar al cliente.');
        } finally {
            setSearching(false);
        }
    });

    $('#openNewClientModal').on('click', () => openPurchaseModal(true));
    $('#openExistingPurchaseModal').on('click', () => openPurchaseModal(false));

    $('.loyalty-check-input').on('input', function () {
        const hours = Number($('#quantityHours').val()) || 0;
        $('#purchaseChecksPreview').text(hours);
    });

    $('#loyaltyPurchaseForm').on('submit', async function (event) {
        event.preventDefault();
        const saveButton = document.getElementById('saveLoyaltyPurchase');
        saveButton.disabled = true;
        saveButton.textContent = 'Guardando...';

        try {
            const response = await fetch(page.dataset.storeUrl, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: new FormData(this),
            });
            const data = await response.json();
            if (!response.ok) throw data;

            currentClient = data.client;
            renderClient(data.client);
            $('#loyaltyClientNotFound').addClass('d-none');
            $('#loyaltyClientResult').removeClass('d-none');
            purchaseModal.hide();
            showMessage('success', data.message);
        } catch (error) {
            showMessage('error', firstError(error));
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = 'Confirmar compra';
        }
    });

    function renderSearchResult(data) {
        $('#loyaltyClientResult').toggleClass('d-none', !data.found);
        $('#loyaltyClientNotFound').toggleClass('d-none', data.found);
        if (data.found) renderClient(data.client);
    }

    function renderClient(client) {
        $('#loyaltyClientName').text(client.full_name);
        $('#loyaltyClientDocumentType').text(client.document_type);
        $('#loyaltyClientDocumentNumber').text(client.document_number);
        $('#loyaltyClientPhone').text(client.phone || 'No registrado');
        $('#loyaltyCardNumber').text(client.card_number || 'Se creará con la primera compra');
        $('#loyaltyCurrentChecks').text(client.current_checks);
        $('#loyaltyTotalChecks').text(client.total_checks);
        $('#loyaltyPendingRewards').text(client.pending_rewards);
        $('#loyaltyAutomaticReward').toggleClass('d-none', !client.next_reward);
        $('#loyaltyAutomaticRewardName').text(client.next_reward?.name || '');
        $('#loyaltyAutomaticRewardDescription').text(client.next_reward?.description || '');
    }

    function openPurchaseModal(isNewClient) {
        document.getElementById('loyaltyPurchaseForm').reset();
        $('#purchaseDocumentType').val(searchedDocumentType);
        $('#purchaseDocumentNumber').val(searchedDocumentNumber);
        $('#newLoyaltyClientFields').toggleClass('d-none', !isNewClient);
        $('#loyaltyModalTitle').text(isNewClient ? 'Registrar cliente y primera compra' : `Registrar compra de ${currentClient.full_name}`);
        ['lastnamePat', 'lastnameMat', 'namesClient', 'birthdayClient'].forEach(id => document.getElementById(id).required = isNewClient);

        $('#purchaseChecksPreview').text('0');
        renderPurchaseReward(isNewClient ? null : currentClient.next_reward);
        purchaseModal.show();
    }

    function renderPurchaseReward(reward) {
        const notice = $('#purchaseRewardNotice');
        notice.removeClass('alert-warning alert-secondary');

        if (reward) {
            notice.addClass('alert-warning');
            $('#purchaseRewardTitle').text('Premio pendiente: aplicar en esta compra');
            $('#purchaseRewardText').text(`${reward.name}. ${reward.description || ''}`);
            return;
        }

        notice.addClass('alert-secondary');
        $('#purchaseRewardTitle').text('Sin premio pendiente');
        $('#purchaseRewardText').text('Realiza la venta normal en Wally. Los premios obtenidos ahora quedarán para la siguiente compra.');
    }

    function setSearching(searching) {
        const button = document.getElementById('searchLoyaltyClient');
        button.disabled = searching;
        button.innerHTML = searching ? '<span class="spinner-border spinner-border-sm me-1"></span> Buscando...' : '<i class="mdi mdi-account-search-outline me-1"></i> Buscar cliente';
    }

    function isValidDocument(type, number) {
        if (type === '01') return /^\d{8}$/.test(number);
        if (type === '06') return /^\d{11}$/.test(number);
        return /^[A-Z0-9-]{1,20}$/.test(number);
    }

    function documentValidationMessage(type) {
        if (type === '01') return 'El DNI debe tener exactamente 8 dígitos.';
        if (type === '06') return 'El RUC debe tener exactamente 11 dígitos.';
        return 'El documento solo puede contener letras, números y guiones.';
    }

    function firstError(error) {
        if (error.errors) {
            const firstField = Object.values(error.errors)[0];
            return Array.isArray(firstField) ? firstField[0] : firstField;
        }
        return error.message || 'No se pudo registrar la compra.';
    }

    function showMessage(icon, message) {
        Swal.fire({ icon, text: message, confirmButtonText: 'Aceptar' });
    }
});

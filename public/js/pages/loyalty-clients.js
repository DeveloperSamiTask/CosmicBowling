$(function () {
    const page = document.getElementById('loyaltyClientsPage');
    if (!page) return;

    const detailModal = new bootstrap.Modal(document.getElementById('loyaltyClientDetailModal'));
    let clients = [];

    loadClients();

    $('#loyaltyClientsSearch').on('input', renderClients);
    $('#loyaltyOriginFilter').on('change', renderClients);
    $('#pendingRewardsOnly').on('change', renderClients);

    $('#loyaltyClientsTableBody').on('click', '.show-loyalty-detail', function () {
        loadClientDetail(this.dataset.clientId);
    });

    async function loadClients() {
        try {
            const response = await fetch(page.dataset.listUrl, { headers: { Accept: 'application/json' } });
            const result = await response.json();
            if (!response.ok) throw result;
            clients = result.data || [];
            renderClients();
        } catch (error) {
            renderTableMessage(error.message || 'No se pudo cargar el listado.');
        }
    }

    function renderClients() {
        const search = $('#loyaltyClientsSearch').val().trim().toLowerCase();
        const origin = $('#loyaltyOriginFilter').val();
        const pendingOnly = $('#pendingRewardsOnly').is(':checked');

        const filtered = clients.filter(client => {
            const searchable = `${client.full_name} ${client.document_number} ${client.card_number}`.toLowerCase();
            const pending = Number(client.rewards_5_pending) + Number(client.rewards_10_pending);
            return (!search || searchable.includes(search))
                && (!origin || client.registration_source === origin)
                && (!pendingOnly || pending > 0);
        });

        const body = document.getElementById('loyaltyClientsTableBody');
        if (!filtered.length) {
            renderTableMessage('No se encontraron clientes con esos filtros.');
            $('#loyaltyClientsCount').text('0 clientes');
            return;
        }

        body.innerHTML = filtered.map(client => `
            <tr>
                <td>
                    <strong>${escapeHtml(client.full_name)}</strong><br>
                    <small class="text-muted">${escapeHtml(client.document_type)} ${escapeHtml(client.document_number)} · ${escapeHtml(client.card_number)}</small>
                </td>
                <td><span class="badge ${client.registration_source === 'atc' ? 'bg-label-info' : 'bg-label-primary'}">${client.registration_source === 'atc' ? 'ATC' : 'Web'}</span></td>
                <td><strong>${Number(client.current_checks)} / 10</strong><br><small class="text-muted">Ciclo ${Number(client.current_cycle)}</small></td>
                <td>${Number(client.total_checks)}</td>
                <td>${rewardSummary(client.rewards_5_pending, client.rewards_5_used)}</td>
                <td>${rewardSummary(client.rewards_10_pending, client.rewards_10_used)}</td>
                <td><button type="button" class="btn btn-sm btn-outline-primary show-loyalty-detail" data-client-id="${Number(client.id)}"><i class="mdi mdi-eye-outline me-1"></i>Ver detalle</button></td>
            </tr>
        `).join('');

        $('#loyaltyClientsCount').text(`${filtered.length} de ${clients.length} clientes`);
    }

    async function loadClientDetail(clientId) {
        try {
            const response = await fetch(`${page.dataset.detailUrl}/${encodeURIComponent(clientId)}`, { headers: { Accept: 'application/json' } });
            const data = await response.json();
            if (!response.ok) throw data;
            renderDetail(data);
            detailModal.show();
        } catch (error) {
            Swal.fire({ icon: 'error', text: error.message || 'No se pudo cargar el detalle.', confirmButtonText: 'Aceptar' });
        }
    }

    function renderDetail(data) {
        const client = data.client;
        $('#detailClientName').text(client.full_name);
        $('#detailClientIdentity').text(`${client.document_type} ${client.document_number}`);
        $('#detailCardNumber').text(client.card_number);
        $('#detailCurrentChecks').text(client.current_checks);
        $('#detailTotalChecks').text(client.total_checks);
        $('#detailCurrentCycle').text(client.current_cycle);

        document.getElementById('loyaltyMovementsBody').innerHTML = data.movements.length
            ? data.movements.map(movement => `
                <tr>
                    <td>${escapeHtml(movement.created_at || '-')}</td>
                    <td>
                        ${escapeHtml(movement.game || movement.type)}<br>
                        <small class="text-muted">${Number(movement.quantity_lane || 0)} pistas · ${Number(movement.quantity_hours || 0)} horas</small>
                    </td>
                    <td><span class="badge bg-label-success">+${Number(movement.checks)}</span></td>
                    <td>${Number(movement.checks_before)} → ${Number(movement.checks_after)}<br><small class="text-muted">Ciclo ${Number(movement.cycle_before)} → ${Number(movement.cycle_after)}</small></td>
                    <td>${escapeHtml(movement.registered_by || 'Sistema web')}</td>
                </tr>
            `).join('')
            : '<tr><td colspan="5" class="text-center text-muted py-3">No existen movimientos.</td></tr>';

        document.getElementById('loyaltyRewardsBody').innerHTML = data.rewards.length
            ? data.rewards.map(reward => `
                <tr>
                    <td><strong>${escapeHtml(reward.name)}</strong><br><small class="text-muted">${escapeHtml(reward.description || '')}</small></td>
                    <td>${Number(reward.milestone)} checks</td>
                    <td>${Number(reward.cycle)}</td>
                    <td>${rewardStatus(reward.status)}</td>
                    <td>${escapeHtml(reward.earned_at || '-')}</td>
                    <td>${escapeHtml(reward.redeemed_at || '-')}</td>
                </tr>
            `).join('')
            : '<tr><td colspan="6" class="text-center text-muted py-3">Este cliente todavía no tiene premios.</td></tr>';
    }

    function rewardSummary(pending, used) {
        return `<span class="text-warning">Pendientes: ${Number(pending)}</span><br><small class="text-muted">Usados: ${Number(used)}</small>`;
    }

    function rewardStatus(status) {
        if (status === 'pending') return '<span class="badge bg-label-warning">Pendiente</span>';
        if (status === 'used') return '<span class="badge bg-label-success">Utilizado</span>';
        return `<span class="badge bg-label-secondary">${escapeHtml(status)}</span>`;
    }

    function renderTableMessage(message) {
        document.getElementById('loyaltyClientsTableBody').innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">${escapeHtml(message)}</td></tr>`;
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }
});

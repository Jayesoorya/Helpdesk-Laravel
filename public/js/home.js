
        new Vue({
            el: '#app',
            data: {
                tickets: [],
                newTicket: {
                    ticket: '',
                    description: '',
                    status: 'Open'
                },
                updateTicketData: {
                    id: '',
                    ticket: '',
                    description: '',
                    status: ''
                }
            },
            methods: {
                fetchTickets() {
                    const token = localStorage.getItem('token');
                    axios.get('/api/tickets', {
                        headers: { Authorization: `Bearer ${token}` }
                    }).then(res => {
                        this.tickets = res.data.tickets;
                    }).catch(err => {
                        console.error(err);
                    });
                },
                createTicket() {
                    const token = localStorage.getItem('token');
                    axios.post('/api/tickets', this.newTicket, {
                        headers: { Authorization: `Bearer ${token}` }
                    }).then(res => {
                        this.fetchTickets();
                        $('#createTicketModal').modal('hide');
                    }).catch(err => {
                        console.error(err);
                    });
                },
                openUpdateModal(ticket) {
                    this.updateTicketData = Object.assign({}, ticket);
                    $('#updateTicketModal').modal('show');
                },
                updateTicket() {
                    const token = localStorage.getItem('token');
                    axios.post(`/api/tickets/${this.updateTicketData.id}`, this.updateTicketData, {
                        headers: { Authorization: `Bearer ${token}` }
                    }).then(res => {
                        this.fetchTickets();
                        $('#updateTicketModal').modal('hide');
                    }).catch(err => {
                        console.error(err);
                    });
                },
                deleteTicket(id) {
                    const token = localStorage.getItem('token');
                    if (confirm('Are you sure?')) {
                        axios.delete(`/api/tickets/${id}`, {
                            headers: { Authorization: `Bearer ${token}` }
                        }).then(res => {
                            this.fetchTickets();
                        }).catch(err => {
                            console.error(err);
                        });
                    }
                },
                logout() {
                    localStorage.removeItem('token');
                    window.location.href = '/';
                },
                goToProfile() {
                    window.location.href = '/profile';
                }
            },
            mounted() {
                this.fetchTickets();
            }
        });
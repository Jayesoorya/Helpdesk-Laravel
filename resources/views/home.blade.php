<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Helpdesk Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome (for icons like user-circle) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head>
<body>

    <div id="app" class="container-fluid">
        <!-- Top Buttons -->
        <div class="text-right mt-4">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#createTicketModal">Create Ticket</button>
            <button class="btn btn-info" @click="logout">
                Logout
            </button>
            <button class="btn btn-outline-primary" @click="goToProfile">
                <i class="fas fa-user-circle"></i> Profile
            </button>
        </div>

        <h1>Helpdesk Laravel</h1>
        <h2 class="mt-4">Welcome,</h2>

        <!-- Ticket Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover mt-5">
                <thead class="thead-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Ticket</th>
                        <th>View Details</th>
                        <th>Status</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="tickets.length === 0">
                        <td colspan="6" class="text-center">No tickets found</td>
                    </tr>
                    <tr v-for="(ticket, index) in tickets" :key="ticket.id">
                        <td>@{{ index + 1 }}</td>
                        <td>@{{ ticket.ticket }}</td>
                        <td><a class="btn btn-info" :href="'/details/' + ticket.id">Details</a></td>
                        <td>@{{ ticket.status }}</td>
                        <td><button class="btn btn-success" @click="openUpdateModal(ticket)">Update</button></td>
                        <td><button class="btn btn-danger" @click="deleteTicket(ticket.id)">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Create Ticket Modal -->
        <div class="modal fade" id="createTicketModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="createTicket">
                            <div class="form-group">
                                <label>Ticket:</label>
                                <input class="form-control" v-model="newTicket.ticket" required>
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <input class="form-control" v-model="newTicket.description">
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" v-model="newTicket.status">
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Create</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Ticket Modal -->
        <div class="modal fade" id="updateTicketModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="updateTicket">
                            <div class="form-group">
                                <label>Ticket:</label>
                                <input class="form-control" v-model="updateTicketData.ticket" required>
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <input class="form-control" v-model="updateTicketData.description">
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" v-model="updateTicketData.status">
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- jQuery, Bootstrap, Vue, Axios -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Vue App Logic -->
    <script>
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
    </script>

</body>
</html>

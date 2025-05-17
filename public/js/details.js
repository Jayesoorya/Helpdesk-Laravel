 new Vue({
            el: '#app',
            data: {
                ticket: null
            },
            created() {
                const ticketId = window.location.pathname.split('/').pop();
                const token = localStorage.getItem('token');

                axios.get(`/api/tickets/${ticketId}`, {
                    headers: { Authorization: `Bearer ${token}` }
                })
                .then(res => {
                    if (res.data.status) {
                        this.ticket = res.data.ticket;
                    } else {
                        alert('Failed to load ticket details.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error loading ticket.');
                });
            },
            
        });
var app = new Vue({
    el: '#app',
    data() {
        return {
            customers: {},
            customer: {},
            state: '',
            city: '',
            first_name: '',
            last_name: '',
            address: '',
            phone: '',
            email: '',
            msg: '',
            success: false,
            err: false
        }
    },
    methods: {
        // get all the customers from the database
        getCustomers() {
            axios.get(`http://slimapp/api/customers`).then(res => {
                this.customers = res.data
            }).catch(err => {
                console.log(err)
            })
        },
        //Get single customer from the Database by id
        getCustomerById(id) {
            axios.get(`http://slimapp/api/customer/${id}`).then(res => {
                this.customer = res.data
            }).catch(err => {
                console.log('Unable to get the Customer')
            })
        },
        // Rest the customer object
        reset() {
            this.customer = {}
        },
        // Add new customer
        addCustomer() {
            var formData = new FormData()
            formData.append('first_name', this.first_name)
            formData.append('last_name', this.last_name)
            formData.append('city', this.city)
            formData.append('phone', this.phone)
            formData.append('address', this.address)
            formData.append('state', this.state)
            formData.append('email', this.email)
            formData.append('phone', this.phone)
            axios.post(`http://slimapp/api/customer/add`, formData).then(res => {
                if (res.data.success) {
                    this.success = res.data.success
                    this.first_name = ''
                    this.last_name = ''
                    this.city = ''
                    this.address = ''
                    this.state = ''
                    this.phone = ''
                    this.email = ''
                    this.msg = res.data.msg
                    this.success = res.data.success
                    this.getCustomers()
                    setInterval(() => {
                        this.msg = ''
                        this.success = false
                    }, 5000)

                } else {
                    this.msg = 'Unable to add the Customer'
                    this.err = true
                    setInterval(() => {
                        this.msg = ''
                        this.err = false
                    }, 5000)
                }
            }).catch(err => { console.log(err) })
        },
        // Delete a customer by ID delete request
        deleteCustomer(id) {
            axios.delete(`http://slimapp/api/customer/delete/${id}`).then(res => {
                if (res.data.success) {
                    this.success = res.data.success
                    this.msg = res.data.msg
                    setInterval(() => {
                        this.msg = ''
                        this.success = false
                    }, 5000)
                    this.getCustomers()
                } else {
                    this.err = true
                    this.msg = res.data.msg
                    setInterval(() => {
                        this.msg = ''
                        this.err = false
                    }, 5000)
                }
            }).catch(err => {
                console.log(err)
            })
        },
        // Updata a customer py id put request
        updateCustomer() {
            axios.put(`http://slimapp/api/customer/update/${this.customer.id}`, this.customer).then(res => {
                if (res.data.success) {
                    this.customer = {}
                    this.msg = res.data.msg
                    this.success = res.data.success
                    this.getCustomers()
                    setInterval(() => {
                        this.msg = ''
                        this.success = false
                    }, 5000)
                }
            }).catch(err => {
                this.msg = 'Unable to add the Customer'
                this.err = true
                setInterval(() => {
                    this.msg = ''
                    this.err = false
                }, 5000)
            })
        },
        // Open edit Modal and fetch the user detail
        editCustomer(id) {
            this.getCustomerById(id)
        }
    },
    created() {
        this.getCustomers()
    }
})
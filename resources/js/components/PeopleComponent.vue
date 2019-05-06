<template>
    <div class="mt-4">
    <form action="./api/people" method="POST" @submit.prevent="addPeople()">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" v-model="person.first_name" id="first_name" />
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" v-model="person.last_name" id="last_name" />
        </div>

        <div class="form-group">
            <label for="age">Age</label>
            <input type="text" class="form-control" v-model="person.age" id="age" />
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" class="form-control" v-model="person.email" id="email" />
        </div>

        <div class="form-group">
            <label for="secret">Secret</label>
            <input type="text" class="form-control" v-model="person.secret" id="secret">
        </div>

        <button class="btn btn-primary mb-4">submit</button>
    </form>
        <div class="card card-body mb-2" v-for="(record, key, index) in people" v-bind:key="record.id">
            <p>Emails: {{ record.emails }}</p>
            <p>People: {{ record.person }}</p>
            <div v-if="key <  people.length - 1">
                <hr>
                <button  @click="deletePeople(record.id)" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</template>

<script>
function Person({ first_name, last_name, age, email, secret}) {
    this.person.first_name = first_name;
    this.person.last_name = last_name;
    this.person.age = age;
    this.person.email = email;
    this.person.secret = secret;
  }

export default {
  data() {
    return {
      people: [],
      person: {
        first_name: '',
        last_name: '',
        age: '',
        email: '',
        secret: ''
      },
      pagination: {},
      edit: false
    };
  },
  created() {
    this.fetchPeople();
  },
  methods: {
    fetchPeople(page_url) {
      let vm = this;
      page_url = page_url || '/api/people';

      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.people = res;

          if (this.people.length === 0) {
            const requiredPeople = {
            data:[
                {
                    first_name: "cody",
                    last_name: "duder",
                    age:38,
                    email: "codyduder@causelabs.com",
                    secret: "VXNlIHRoaXMgc2VjcmV0IHBocmFzZSBzb21ld2hlcmUgaW4geW91ciBjb2RlJ3MgY29tbWVudHM="
                },
                {
                    first_name: "ladee",
                    last_name: "linter",
                    age: 99,
                    email: "lindaladee@causelabs.com",
                    secret: "cmVzb3VyY2UgdmlvbGF0aW9u"
                }
            ]};

            axios.post('/api/people', requiredPeople)
            .then()
            .then(data => {
                this.clearForm();
                this.fetchPeople();
            })
            .catch(err => console.log(err));
            }
        })
        .catch(err => console.log(err));
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };
      this.pagination = pagination;
    },
    addPeople() {
        axios.post('/api/people',{
            'data': [{
                first_name: this.person.first_name,
                last_name: this.person.last_name,
                age: this.person.age,
                email: this.person.email,
                secret: this.person.secret
            }]
        }).then()
          .then(data => {
            this.clearForm();
            alert('People Added');
            this.fetchPeople();
          })
          .catch(err => console.log(err));
    },
    deletePeople(id) {
      if (confirm('Are You Sure?')) {
        const url = `/api/people/${id}`;
        axios.delete(url)
          .then()
          .then(data => {
            alert('People Removed');
            this.fetchPeople();
          })
          .catch(err => console.log(err));
      }
    },
    clearForm() {
      this.edit = false;
      this.people.id = null;
      this.people.people_id = null;
      this.people.first_name = '';
      this.people.last_name = '';
    }
  }
};
</script>


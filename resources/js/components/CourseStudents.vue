<template>
  <div>
    <div class="page-title">
      <h1>Alumnos de {{ course.title }}</h1>
      <div v-if="user.type == 'Admin'">
        <a
          :href="'/course/' + course.id + '/assign-students'"
          class="btn primary-button"
        >Assignar alumnos</a>
      </div>
    </div>
    <div class="list">
      <div class="list-heading">
        <div class="list-row">
          <h3>Nombre</h3>
          <h3>Apellido</h3>
          <h3>Email</h3>
        </div>
      </div>
      <div class="list-content">
        <div class="list-row" v-bind:key="i" v-for="(student, i) in course.students">
          <a :href="'/student/' + student.id" class="list-data">
            {{
            student.name
            }}
          </a>
          <p class="list-data">{{ student.surname }}</p>
          <p>{{ student.email }}</p>
        </div>
      </div>
    </div>
    <div>
      <a @click.prevent @click="goBack()" href class="list-actions">&#8592; Volver</a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["course"],
  data() {
    return {
      user: {},
    };
  },
  methods: {
    getLoggedUser() {
      axios.get("/loggeduser").then((response) => {
        this.user = response.data;
      });
    },
    goBack() {
      window.history.back();
    },
  },
  mounted() {
    this.getLoggedUser();
    console.log("Component mounted.");
  },
};
</script>

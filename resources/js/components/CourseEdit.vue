<template>
  <div>
    <div class="page-title">
      <h1>Actualizar datos del curso</h1>
    </div>
    <div class="form">
      <div class="form-group">
        <label>Titutlo</label>
        <input class="form-control" type="text" name="title" v-model="course.title" />
      </div>
      <div class="form-group">
        <label>Descripcion</label>
        <input class="form-control" type="text" name="description" v-model="course.description" />
      </div>
      <div class="row">
        <div class="col">
          <label>Fecha primero dia</label>
          <input class="form-control" type="date" name="start_date" v-model="course.start_date" />
        </div>
        <div class="col">
          <label>Fecha ultimo dia</label>
          <input class="form-control" type="date" name="end_date" v-model="course.end_date" />
        </div>
      </div>
      <div class="form-submit">
        <a @click.prevent @click="goBack()" href class="list-actions">&#8592; Volver</a>
        <button class="btn primary-button" @click="editCourse()">Editar</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["course"],
  data() {
    return {};
  },
  methods: {
    editCourse() {
      axios
        .patch("/courses/" + this.course.id, this.course)
        .then((response) => {
          window.location.replace("/courses");
        });
    },
    goBack() {
      window.history.back();
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
};
</script>

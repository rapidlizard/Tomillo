<template>
  <div>
    <div class="page-title">
      <h1>Actualizar Datos del Alumno</h1>
    </div>
    <div class="form">
      <div class="form-group">
        <label>Nombre</label>
        <input type="text" v-model="student.name" name="name" class="form-control" required />
      </div>
      <div class="form-group">
        <label>Apellido</label>
        <input type="text" v-model="student.surname" name="surname" class="form-control" required />
      </div>
      <div class="form-group">
        <label>Nacionalidad</label>
        <input
          type="text"
          v-model="student.nationality"
          name="nationality"
          class="form-control"
          required
        />
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" v-model="student.email" name="email" class="form-control" required />
      </div>
      <div class="form-group">
        <label>Sexo</label>
        <select v-model="student.gender" class="form-control">
          <option value="Hombre">Hombre</option>
          <option value="Mujer">Mujer</option>
          <option value="Otro">Otro</option>
        </select>
      </div>
      <div class="form-submit">
        <a @click.prevent @click="goBack()" href class="list-actions">&#8592; Volver</a>
        <button class="btn primary-button" @click="updateStudentInfo(student)" value="Crear">Editar</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["student"],
  data() {
    return {};
  },
  mounted() {
    console.log("Component mounted.");
  },
  methods: {
    updateStudentInfo(student) {
      axios
        .patch("/students/" + student.id, this.student)
        .then(window.location.replace("/students"));
    },
    goBack() {
      window.history.back();
    },
  },
};
</script>

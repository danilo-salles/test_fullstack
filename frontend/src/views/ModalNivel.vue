<template>
      <div class="modal">
        <div class="modal-header">{{ modalTitle }}</div>
        <form @submit.prevent="saveNivel">
          <div class="modal-body">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" v-model="currentNivel.nome" :class="{ 'loading': loading }" :disabled="loading" />
          </div>
          <div class="modal-footer">
            <button type="submit" :disabled="loading">{{ editMode ? 'Alterar' : 'Cadastrar' }}</button>
            <button type="button" @click="closeModal" :disabled="loading">Cancelar</button>
          </div>
          <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
        </form>
      </div>
    </template>
    
    <script>
    export default {
      props: {
        modalTitle: String,
        editMode: Boolean
      },
      data() {
        return {
          currentNivel: {
            id: null,
            nome: ''
          },
          loading: false,
          successMessage: ''
        };
      },
      methods: {
        saveNivel() {
          this.loading = true;
          this.modalErrorMessage = '';
          setTimeout(() => {
    
            this.successMessage = `${this.editMode ? 'Nível alterado' : 'Nível cadastrado'} com sucesso!`;
            this.currentNivel.nome = '';
            this.loading = false;
    
            setTimeout(() => {
              this.successMessage = '';
              this.$emit('close');
            }, 1000);
          }, 2000);
        },
        closeModal() {
          this.$emit('close');
        }
      }
    };
    </script>
    
    <style scoped>
    .modal {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 300px;
    }
    
    .modal-header {
      background-color: #1f264a;
      color: white;
      padding: 10px;
      font-size: 18px;
      font-weight: bold;
      border-top-left-radius: 5px;
      border-top-right-radius: 5px;
    }
    
    .modal-body {
      margin-bottom: 10px;
    }
    
    .modal-body label {
      display: block;
      margin-bottom: 5px;
    }
    
    .modal-body input {
      width: calc(100% - 10px);
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    
    .modal-body input.loading {
      opacity: 0.7;
    }
    
    .modal-footer {
      display: flex;
      justify-content: flex-end;
    }
    
    .modal-footer button {
      margin-left: 10px;
      padding: 8px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      background-color: #007bff;
      color: white;
      transition: background-color 0.3s;
    }
    
    .modal-footer button:disabled {
      cursor: not-allowed;
      opacity: 0.6;
    }
    
    .modal-footer button:hover {
      background-color: #0056b3;
    }
    
    .success-message {
      margin-top: 10px;
      padding: 8px;
      background-color: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      border-radius: 4px;
    }
    </style>
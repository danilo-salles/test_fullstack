<template>
      <div class="niveis-page">
        <aside class="sidebar">
          <h1>Teste Fullstack</h1>
          <div class="button-container">
            <router-link to="/niveis" class="btn">Nível</router-link>
            <router-link to="/desenvolvedor" class="btn">Desenvolvedores</router-link>
            <router-link to="/" class="btn">Home</router-link>
          </div>
        </aside>
    
        <main class="content">
          <header class="header">
            <div class="container">
              <h1>Níveis</h1>
            </div>
          </header>
    
          <div class="container">
            <div class="table-container">
              <table v-if="!loading" class="table">
                <thead>
                  <tr>
                    <th @click="sortBy('id')">ID <i v-if="sortColumn === 'id'"
                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i></th>
                    <th @click="sortBy('nivel')">Nível <i v-if="sortColumn === 'nivel'"
                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i></th>
                    <th @click="sortBy('quantidade')">Quantidade de Desenvolvedores <i v-if="sortColumn === 'quantidade'"
                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i></th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="nivel in niveis" :key="nivel.id" class="hoverable">
                    <td>{{ nivel.id }}</td>
                    <td>{{ nivel.nivel }}</td>
                    <td>{{ nivel.quantidade }}</td>
                    <td>
                      <button @click="openModal('update', nivel)" class="btn-edit">
                        <i class="fas fa-pencil-alt"></i> Alterar
                      </button>
                      <button @click="confirmDelete(nivel)" class="btn-remove">
                        <i class="fas fa-trash-alt"></i> Remover
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div v-if="loading" class="loading-container">
                <img src="@/assets/loading-loading-forever.gif" alt="Carregando..." class="loading-gif">
                <p>Carregando...</p>
              </div>
            </div>
    
            <div class="button-container" v-if="!loading">
              <button class="btnAdd" @click="openModal('create')">Adicionar</button>
            </div>
          </div>
    
          <div class="pagination-container" v-if="meta && meta.last_page > 1">
            <button v-if="meta.current_page > 1" @click="fetchNiveis(meta.current_page - 1)" class="btn">
              Anterior
            </button>
            <span>Página {{ meta.current_page }} de {{ meta.last_page }}</span>
            <button v-if="meta.current_page < meta.last_page" @click="fetchNiveis(meta.current_page + 1)" class="btn">
              Próxima
            </button>
          </div>
    
          <div v-if="showModal" class="modal-background">
            <div class="modal">
              <h2>{{ modalMode === 'create' ? 'Adicionar Nível' : 'Editar Nível' }}</h2>
              <form @submit.prevent="saveNivel">
                <div class="form-group">
                  <label for="nivel">Nível:</label>
                  <input v-model="currentNivel.nivel" :class="{ 'input-field': true, 'invalid': !validarNivel }"
                    class="input-field" id="nivel" placeholder="Nível..." type="text" maxlength="255" required />
                </div>
    
                <div v-if="modalSuccessMessage" class="success-message">
                  <p>{{ modalSuccessMessage }}</p>
                  <button @click="closeModal" class="btn">Fechar</button>
                </div>
    
                <div v-else class="button-group">
                  <button type="submit" class="btn" :class="{ 'btn-loading': loadingModal }" :disabled="loadingModal">
                    {{ loadingModal ? 'Carregando...' : (modalMode === 'create' ? 'Adicionar' : 'Salvar') }}
                  </button>
                  <button v-if="!loadingModal" @click="closeModal" class="btn">
                    Cancelar
                  </button>
                </div>
    
                <div v-if="modalErrorMessage" class="error-message">
                  <p>{{ modalErrorMessage }}</p>
                </div>
              </form>
            </div>
          </div>
    
          <div v-if="showConfirmDelete" class="modal-background">
            <div class="modal">
              <p v-if="!loadingDelete">Tem certeza que deseja excluir o Nível "{{ currentNivel.nivel }}"?</p>
              <div v-if="loadingDelete" class="loading-message">
                Excluindo...
              </div>
              <button v-if="!loadingDelete" @click="deleteNivel" class="btn">Sim</button>
              <button v-if="!loadingDelete" @click="closeConfirmDelete" class="btn btn-cancel">Não</button>
            </div>
          </div>
    
          <div v-if="deleteSuccessMessage || deleteErrorMessage" class="modal-background">
            <div class="modal">
              <p v-if="deleteSuccessMessage">{{ deleteSuccessMessage }}</p>
              <p v-if="deleteErrorMessage">{{ deleteErrorMessage }}</p>
              <button @click="clearDeleteMessages" class="btn">Fechar</button>
            </div>
          </div>
        </main>
      </div>
    </template>
    
    <script>
    export default {
      name: 'NiveisPage',
      data() {
        return {
          niveis: [],
          loading: false,
          loadingModal: false,
          showModal: false,
          showConfirmDelete: false,
          loadingDelete: false,
          deleteSuccessMessage: '',
          deleteErrorMessage: '',
          currentNivel: {
            id: null,
            nivel: ''
          },
          modalMode: '',
          modalSuccessMessage: '',
          modalErrorMessage: '',
          meta: {
            total: 0,
            per_page: 10,
            current_page: 1,
            last_page: 1
          },
          sortColumn: 'id',
          sortDirection: 'asc'
        };
      },
      created() {
        this.fetchNiveis();
      },
      methods: {
        async fetchNiveis(page = 1) {
          this.loading = true;
          try {
            const response = await fetch(`http://localhost:8000/api/niveis?page=${page}&per_page=${this.meta.per_page}`);
            const data = await response.json();
    
            const countResponse = await fetch('http://localhost:8000/api/niveis/desenvolvedores/count');
            let countData = [];
            if (countResponse.ok) {
              const countDataResponse = await countResponse.json();
              countData = countDataResponse.data || [];
            } else {
              console.error('Erro ao obter contagem de desenvolvedores:', countResponse.statusText);
            }
    
            const niveis = data.data.map(nivel => {
              const count = countData.find(c => c.nivel === nivel.nivel)?.quantidade || 0;
              return {
                ...nivel,
                quantidade: count
              };
            });
    
            this.niveis = niveis;
            this.meta.total = data.meta.total;
            this.meta.per_page = data.meta.per_page;
            this.meta.current_page = data.meta.current_page;
            this.meta.last_page = data.meta.last_page;
            this.loading = false;
          } catch (error) {
            console.error('Erro ao buscar Níveis:', error);
            this.loading = false;
          }
        },
        openModal(mode, nivel = null) {
          if (mode === 'create') {
            this.modalMode = 'create';
            this.currentNivel = {
              id: null,
              nivel: ''
            };
          } else if (mode === 'update' && nivel) {
            this.modalMode = 'update';
            this.currentNivel = {
              id: nivel.id,
              nivel: nivel.nivel
            };
          }
          this.showModal = true;
        },
        closeModal() {
          this.showModal = false;
          this.modalSuccessMessage = '';
          this.modalErrorMessage = '';
        },
        confirmDelete(nivel) {
          this.currentNivel = nivel;
          this.showConfirmDelete = true;
        },
        closeConfirmDelete() {
          this.showConfirmDelete = false;
        },
        async saveNivel() {
          this.loadingModal = true;
          const method = this.modalMode === 'create' ? 'POST' : 'PUT';
          const url = this.modalMode === 'create' ? 'http://localhost:8000/api/niveis' : `http://localhost:8000/api/niveis/${this.currentNivel.id}`;
    
          try {
            const response = await fetch(url, {
              method,
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(this.currentNivel)
            });
    
            if (response.ok) {
              const message = this.modalMode === 'create' ? 'Nível adicionado com sucesso!' : 'Nível atualizado com sucesso!';
              this.modalSuccessMessage = message;
              await this.fetchNiveis(this.meta.current_page);
              this.closeModal();
            } else {
              const errorData = await response.json();
              if (response.status === 409 && errorData.error === 'O Nível jÃ¡ existe') {
                this.modalErrorMessage = errorData.error;
              } else {
                this.modalErrorMessage = errorData.error || 'Ocorreu um erro ao salvar o Nível.';
              }
            }
          } catch (error) {
            console.error('Erro ao salvar Nível:', error);
            this.modalErrorMessage = 'Ocorreu um erro ao salvar o Nível.';
          } finally {
            this.loadingModal = false;
          }
        },
        async deleteNivel() {
          this.loadingDelete = true;
          try {
            const response = await fetch(`http://localhost:8000/api/niveis/${this.currentNivel.id}`, {
              method: 'DELETE'
            });
    
            if (response.ok) {
              this.deleteSuccessMessage = 'Nível excluído com sucesso!';
              this.fetchNiveis(this.meta.current_page);
            } else {
              const errorData = await response.json();
              this.deleteErrorMessage = errorData.message || 'Ocorreu um erro ao excluir o Nível.';
            }
          } catch (error) {
            console.error('Erro ao excluir Nível:', error);
            this.deleteErrorMessage = 'Ocorreu um erro ao excluir o Nível.';
          } finally {
            this.loadingDelete = false;
            this.showConfirmDelete = false;
          }
        },
        clearDeleteMessages() {
          this.deleteSuccessMessage = '';
          this.deleteErrorMessage = '';
        },
        sortBy(column) {
          if (this.sortColumn === column) {
            this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
          } else {
            this.sortColumn = column;
            this.sortDirection = 'asc';
          }
    
          this.niveis.sort((a, b) => {
            let result = 0;
            if (a[column] < b[column]) result = -1;
            if (a[column] > b[column]) result = 1;
            return this.sortDirection === 'asc' ? result : -result;
          });
        }
      },
      computed: {
        validarNivel() {
          return this.currentNivel.nivel.length > 0 && this.currentNivel.nivel.length <= 255;
        }
      }
    };
    </script>
    
    
    <style scoped>
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      height: 100%;
      width: 200px;
      background-color: #1f264a;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 20px;
      z-index: 1000;
    }
    
    .sidebar h1 {
      margin-bottom: 20px;
    }
    
    .sidebar .button-container {
      display: flex;
      flex-direction: column;
      width: 100%;
      align-items: center;
    }
    
    .sidebar .button-container .btn {
      margin: 10px 0;
      width: 65%;
      text-align: center;
    }
    
    .content {
      margin-left: 200px;
      padding: 20px;
      margin-top: -3%;
    }
    
    .header {
      background-color: #1f264a;
      color: white;
      padding: 8px 0;
      text-align: center;
      z-index: 1000;
    }
    
    .loading-container {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      margin-top: 20px;
    }
    
    .loading-gif {
      width: 50px;
      height: auto;
      margin-bottom: 10px;
    }
    
    .header h1 {
      margin-bottom: 3%;
      font-size: 18px;
    }
    
    .container {
      margin: 30px auto 0;
    }
    
    .table-container {
      margin-top: 20px;
      text-align: left;
      width: 100%;
    }
    
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    
    .table th,
    .table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }
    
    .table th {
      background-color: #f2f2f2;
      color: #1f264a;
    }
    
    .loading-message {
      text-align: center;
      margin-top: 20px;
      font-size: 16px;
      color: #1f264a;
    }
    
    .modal-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 2000;
    }
    
    .modal {
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      max-width: 400px;
      width: 100%;
      text-align: center;
    }
    
    .input-field {
      width: 89%;
      padding: 10px;
      margin-bottom: 10px;
      font-size: 16px;
    }
    
    .button-container {
      margin-top: 20px;
      margin-bottom: 20px;
      text-align: left;
    }
    
    .btn {
      display: inline-block;
      padding: 10px 20px;
      margin: 0 10px;
      font-size: 16px;
      text-align: center;
      text-decoration: none;
      color: #1f264a;
      background-color: white;
      border: 2px solid #1f264a;
      border-radius: 5px;
      transition: background-color 0.3s, color 0.3s;
    }
    
    .btnAdd {
      display: inline-block;
      padding: 10px 20px;
      font-size: 16px;
      text-align: center;
      text-decoration: none;
      color: white;
      background-color: #4CAF50;
      border: 2px solid #4CAF50;
      border-radius: 5px;
      transition: background-color 0.3s, color 0.3s;
    }
    
    .btnAdd:hover {
      background-color: #388E3C;
      border-color: #388E3C;
    }
    
    .btn:hover {
      background-color: #1f264a;
      color: white;
    }
    
    .btn-edit {
      display: inline-block;
      padding: 8px 12px;
      margin-right: 10px;
      font-size: 14px;
      text-align: center;
      text-decoration: none;
      color: rgb(255, 255, 255);
      background-color: #e5d400;
      border: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    
    .btn-edit:hover {
      background-color: #b4a803;
    }
    
    .btn-remove {
      display: inline-block;
      padding: 8px 12px;
      margin-right: 10px;
      font-size: 14px;
      text-align: center;
      text-decoration: none;
      color: white;
      background-color: #e74c3c;
      border: none;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    
    .btn-remove:hover {
      background-color: #c0392b;
    }
    
    .fa-pencil-alt,
    .fa-trash-alt {
      margin-right: 5px;
    }
    
    .fa-trash-alt:hover {
      color: red;
    }
    
    .success-message {
      text-align: center;
      margin-top: 20px;
      color: green;
    }
    
    .error-message {
      text-align: center;
      margin-top: 20px;
      color: red;
    }
    
    .btn-loading {
      cursor: not-allowed;
      opacity: 0.7;
    }
    
    .btn-cancel:hover {
      background-color: #bbb;
      border-color: #bbb;
      color: #333;
    }
    
    .hoverable:hover {
      background-color: #f2f2f2;
    }
    
    @media screen and (max-width: 600px) {
      .sidebar {
        display: none;
      }
    
      .content {
        margin-left: 0;
      }
    }
    
    .navbar {
      overflow: hidden;
      background-color: #333;
    }
    
    .navbar a {
      float: left;
      display: block;
      color: #f2f2f2;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
      font-size: 17px;
    }
    
    .navbar a:hover {
      background-color: #ddd;
      color: black;
    }
    
    .row {
      display: flex;
      flex-wrap: wrap;
    }
    
    .column {
      flex: 50%;
      padding: 10px;
    }
    </style>
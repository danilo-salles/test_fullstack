<template>
    <div class="desenvolvedores-page">
        <aside class="sidebar">
            <h1>Teste Fullstack</h1>
            <div class="button-container">
                <router-link to="/niveis" class="btn">Níveis</router-link>
                <router-link to="/desenvolvedor" class="btn">Desenvolvedores</router-link>
                <router-link to="/" class="btn">Home</router-link>
            </div>
        </aside>

        <main class="content">
            <header class="header">
                <div class="container">
                    <h1>Desenvolvedores</h1>
                </div>
            </header>

            <div class="container">
                <div class="table-container">
                    <table v-if="!loading" class="table">
                        <thead>
                            <tr>
                                <th @click="sortBy('id')">ID <i v-if="sortColumn === 'id'"
                                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                </th>
                                <th @click="sortBy('nome')">Nome <i v-if="sortColumn === 'nome'"
                                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                </th>
                                <th @click="sortBy('sexo')">Sexo <i v-if="sortColumn === 'sexo'"
                                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                </th>
                                <th @click="sortBy('data_nascimento')">Data de Nascimento <i
                                        v-if="sortColumn === 'data_nascimento'"
                                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                </th>
                                <th @click="sortBy('hobby')">Hobby <i v-if="sortColumn === 'hobby'"
                                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                </th>
                                <th @click="sortBy('nivel_nome')">Nível <i v-if="sortColumn === 'nivel_nome'"
                                        :class="sortDirection === 'asc' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                </th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dev in desenvolvedores" :key="dev.id" class="hoverable">
                                <td>{{ dev.id }}</td>
                                <td>{{ dev.nome }}</td>
                                <td>{{ dev.sexo }}</td>
                                <td>{{ dev.data_nascimento }}</td>
                                <td>{{ dev.hobby }}</td>
                                <td>{{ dev.nivel_nome }}</td>
                                <td>
                                    <button @click="openModal('update', dev)" class="btn-edit">
                                        <i class="fas fa-pencil-alt"></i> Alterar
                                    </button>
                                    <button @click="confirmDelete(dev)" class="btn-remove">
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
                <button v-if="meta.current_page > 1" @click="fetchDesenvolvedores(meta.current_page - 1)" class="btn">
                    Anterior
                </button>
                <span>Página{{ meta.current_page }} de {{ meta.last_page }}</span>
                <button v-if="meta.current_page < meta.last_page" @click="fetchDesenvolvedores(meta.current_page + 1)"
                    class="btn">
                    Próxima
                </button>
            </div>

            <div v-if="showModal" class="modal-background">
                <div class="modal">
                    <h2>{{ modalMode === 'create' ? 'Adicionar Desenvolvedor' : 'Editar Desenvolvedor' }}</h2>
                    <form @submit.prevent="saveDesenvolvedor">
                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input v-model="currentDev.nome" :class="{ 'input-field': true, 'invalid': !validarNome }"
                                class="input-field" id="nome" placeholder="Gustavo..." type="text" maxlength="255"
                                required />
                        </div>

                        <div class="form-group">
                            <label for="sexo">Sexo:</label>
                            <select v-model="currentDev.sexo" :class="{ 'input-field': true, 'invalid': !validarSexo }"
                                class="input-field input-sexo" id="sexo" required>
                                <option value="">Selecione o sexo</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="O">Outro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="dataNascimento">Data de Nascimento:</label>
                            <input v-model="currentDev.data_nascimento"
                                :class="{ 'input-field': true, 'invalid': !validarDataNascimento }" class="input-field"
                                id="dataNascimento" type="date" placeholder="Data de Nascimento" required />
                        </div>

                        <div class="form-group">
                            <label for="hobby">Hobby:</label>
                            <input v-model="currentDev.hobby" :class="{ 'input-field': true, 'invalid': !validarHobby }"
                                class="input-field" id="hobby" placeholder="Cantar..." type="text" maxlength="255"
                                required />
                        </div>

                        <div class="form-group">
                            <label for="nivelId">ID do Nível:</label>
                            <input v-model.number="currentDev.nivel_id"
                                :class="{ 'input-field': true, 'invalid': !validarNivelId }" class="input-field"
                                id="nivelId" type="number" placeholder="1" required />
                        </div>

                        <div v-if="modalSuccessMessage" class="success-message">
                            <p>{{ modalSuccessMessage }}</p>
                            <button @click="closeModal" class="btn">Fechar</button>
                        </div>

                        <div v-else class="button-group">
                            <button type="submit" class="btn" :class="{ 'btn-loading': loadingModal }"
                                :disabled="loadingModal">
                                {{ loadingModal ? 'Carregando...' : (modalMode === 'create' ? 'Adicionar' : 'Salvar') }}
                            </button>
                            <button v-if="!loadingModal" @click="closeModal" class="btn btn-cancel">
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
                    <p v-if="!loadingDelete">Tem certeza que deseja excluir o desenvolvedor "{{ currentDev.nome }}"?</p>
                    <div v-if="loadingDelete" class="loading-message">
                        Excluindo...
                    </div>
                    <p v-if="deleteErrorMessage">{{ deleteErrorMessage }}</p>
                    <button v-if="!loadingDelete" @click="deleteDesenvolvedor" class="btn">Sim</button>
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
    name: 'DesenvolvedoresPage',
    data() {
        return {
            desenvolvedores: [],
            loading: false,
            loadingModal: false,
            showModal: false,
            showConfirmDelete: false,
            loadingDelete: false,
            deleteSuccessMessage: '',
            deleteErrorMessage: '',
            currentDev: {
                id: null,
                nome: '',
                sexo: '',
                data_nascimento: '',
                hobby: '',
                nivel_id: null
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
        this.fetchDesenvolvedores();
    },
    methods: {
        fetchDesenvolvedores(page = 1) {
            this.loading = true;
            fetch(`http://localhost:8000/api/desenvolvedores?page=${page}&per_page=${this.meta.per_page}`)
                .then(response => response.json())
                .then(async data => {
                    const desenvolvedores = await Promise.all(data.data.map(async (dev) => {
                        try {
                            if (dev.nivel && dev.nivel.id !== null && dev.nivel.nivel !== undefined) {
                                return {
                                    id: dev.id,
                                    nome: dev.nome,
                                    sexo: dev.sexo,
                                    data_nascimento: dev.data_nascimento,
                                    idade: dev.idade,
                                    hobby: dev.hobby,
                                    nivel_id: dev.nivel.id,
                                    nivel_nome: dev.nivel.nivel
                                };
                            } else {
                                return {
                                    id: dev.id,
                                    nome: dev.nome,
                                    sexo: dev.sexo,
                                    data_nascimento: dev.data_nascimento,
                                    idade: dev.idade,
                                    hobby: dev.hobby,
                                    nivel_id: null,
                                    nivel_nome: 'N/A'
                                };
                            }
                        } catch (error) {
                            console.error('Erro ao formatar desenvolvedor: ', error);
                        }
                    }));

                    this.desenvolvedores = desenvolvedores;
                    this.meta.total = data.meta.total;
                    this.meta.per_page = data.meta.per_page;
                    this.meta.current_page = data.meta.current_page;
                    this.meta.last_page = data.meta.last_page;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Erro ao buscar desenvolvedores: ', error);
                    this.loading = false;
                });
        },
        openModal(mode, dev = null) {
            if (mode === 'create') {
                this.modalMode = 'create';
                this.currentDev = {
                    id: null,
                    nome: '',
                    sexo: '',
                    data_nascimento: '',
                    hobby: '',
                    nivel_id: null
                };
            } else if (mode === 'update' && dev) {
                this.modalMode = 'update';
                this.currentDev = {
                    id: dev.id,
                    nome: dev.nome,
                    sexo: dev.sexo,
                    data_nascimento: dev.data_nascimento,
                    hobby: dev.hobby,
                    nivel_id: dev.nivel_id
                };
            }
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            this.modalSuccessMessage = '';
            this.modalErrorMessage = '';
        },
        confirmDelete(dev) {
            this.currentDev = dev;
            this.showConfirmDelete = true;
        },
        closeConfirmDelete() {
            this.showConfirmDelete = false;
        },
        deleteDesenvolvedor() {
            this.loadingDelete = true;
            fetch(`http://localhost:8000/api/desenvolvedores/${this.currentDev.id}`, {
                method: 'DELETE',
            })
                .then(response => {
                    if (response.ok) {
                        const indexToRemove = this.desenvolvedores.findIndex(dev => dev.id === this.currentDev.id);
                        if (indexToRemove !== -1) {
                            this.desenvolvedores.splice(indexToRemove, 1);
                        }
                        this.deleteSuccessMessage = 'Desenvolvedor excluÃ­do com sucesso!';
                    } else {
                        throw new Error('Erro ao excluir desenvolvedor.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao excluir desenvolvedor: ', error);
                    this.deleteErrorMessage = 'Erro ao excluir desenvolvedor. Por favor, tente novamente.';
                })
                .finally(() => {
                    this.loadingDelete = false;
                    this.showConfirmDelete = false;
                });
        },
        clearDeleteMessages() {
            this.deleteSuccessMessage = '';
            this.deleteErrorMessage = '';
        },
        saveDesenvolvedor() {
            if (!this.validateForm()) {
                return;
            }

            this.loadingModal = true;
            const method = this.modalMode === 'create' ? 'POST' : 'PUT';
            let apiUrl = 'http://localhost:8000/api/desenvolvedores';
            if (this.modalMode === 'update') {
                apiUrl = `/${this.currentDev.id}`;
            }

            fetch(apiUrl, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    nome: this.currentDev.nome,
                    sexo: this.currentDev.sexo,
                    data_nascimento: this.currentDev.data_nascimento,
                    hobby: this.currentDev.hobby,
                    nivel_id: this.currentDev.nivel_id
                }),
            })
                .then(response => {
                    if (response.ok) {
                        this.modalSuccessMessage = `Desenvolvedor ${this.modalMode === 'create' ? 'adicionado' : 'atualizado'} com sucesso!`;
                        this.fetchDesenvolvedores();
                    } else {
                        throw new Error('Erro ao salvar desenvolvedor.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao salvar desenvolvedor: ', error);
                    this.modalErrorMessage = `Erro ao ${this.modalMode === 'create' ? 'adicionar' : 'atualizar'} desenvolvedor. Por favor, tente novamente.`;
                })
                .finally(() => {
                    this.loadingModal = false;
                });
        },
        validateForm() {
            this.validarNome = this.currentDev.nome.trim() !== '';
            this.validarSexo = this.currentDev.sexo.trim() !== '';
            this.validarDataNascimento = this.currentDev.data_nascimento.trim() !== '';
            this.validarHobby = this.currentDev.hobby.trim() !== '';
            this.validarNivelId = this.currentDev.nivel_id !== null;

            return (
                this.validarNome &&
                this.validarSexo &&
                this.validarDataNascimento &&
                this.validarHobby &&
                this.validarNivelId
            );
        },
        sortBy(column) {
            if (this.sortColumn === column) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortColumn = column;
                this.sortDirection = 'asc';
            }

            this.desenvolvedores.sort((a, b) => {
                let modifier = 1;
                if (this.sortDirection === 'desc') modifier = -1;

                if (column === 'id') {
                    return modifier * (a.id - b.id);
                } else if (column === 'nome') {
                    return modifier * a.nome.localeCompare(b.nome);
                } else if (column === 'sexo') {
                    return modifier * a.sexo.localeCompare(b.sexo);
                } else if (column === 'data_nascimento') {
                    return modifier * (new Date(a.data_nascimento) - new Date(b.data_nascimento));
                } else if (column === 'hobby') {
                    return modifier * a.hobby.localeCompare(b.hobby);
                } else if (column === 'nivel_nome') {
                    return modifier * a.nivel_nome.localeCompare(b.nivel_nome);
                } else {
                    return 0;
                }
            });
        }
    },
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

.sidebar .btn {
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

.sidebar .btn:hover {
    background-color: #1f264a;
    color: white;
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

.btn:hover {
    background-color: #1f264a;
    color: white;
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

.pagination-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.pagination-container button {
    margin: 0 5px;
}

.pagination-container span {
    margin: 0 10px;
}

table tbody tr:hover {
    background-color: #f5f5f5;
}

.btn-edit:hover,
.btn-remove:hover {
    filter: brightness(0.8);
}

.input-sexo {
    width: 95%;
}

.form-container {
    margin-bottom: 20px;
    padding: 10px;
    background-color: #f5f5f5;
    border-radius: 5px;
}
</style>
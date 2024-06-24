<template>
      <div class="home-page">
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
            <h1>Teste Fullstack</h1>
          </header>
          <div class="container">
            <p>Bem-vindo á pagína inicial do Teste Fullstack.</p>
          </div>
        </main>
      </div>
    </template>
    
    <style scoped>
    .home-page {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    
    .header {
      background-color: #1f264a;
      color: white;
      padding: 21px 0;
      text-align: center;
    }
    
    
    .header h1 {
      margin-bottom: 3%;
      font-size: 18px;
    }
    
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
    
    .button-container {
      margin-top: 20px;
      margin-bottom: 20px;
      text-align: left;
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
    
    .content {
      margin-left: 200px;
      padding: 20px;
      margin-top: -3%;
    }
    
    .container {
      margin: 30px auto 0;
      max-width: 800px;
    }
    </style>
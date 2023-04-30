import React, { useState } from 'react';
import { Card, Container, Form, Button } from 'react-bootstrap';
import Navigation from '../Component/Navigation';
import axios from 'axios';
import {useNavigate } from 'react-router-dom';

const Login = () => {

  const [login, setLogin] = useState('');
  const [password, setPassword] = useState('');
  const navigate = useNavigate();

  const handleLoginChange = (event) => {
    setLogin(event.target.value);
  };

  const handlePasswordChange = (event) => {
    setPassword(event.target.value);
  };

  const signIn = (event) => {
    event.preventDefault();
    const data = {
      login: login,
      password: password,
    };
    return new Promise ((resolve, reject) => {
      axios.post(`http://127.0.0.1:8000/api/login`, data)
      .then(response => {
  
          localStorage.setItem('token', response.data.data.token);
          navigate('/');
          resolve();
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des données :', error);
        });
    })
    
  };

  return (
    <div>
      <Navigation />
      <Container className="d-flex align-items-center justify-content-center vh-100">
        <Card className="mx-auto" style={{ width: '22rem' }}>
          <Card.Body>
            <Card.Title>Connexion</Card.Title>
            <Form>
              <Form.Group controlId="email">
                <Form.Label>Login</Form.Label>
                <Form.Control type="login" placeholder="Enter your login" value={login} onChange={handleLoginChange} />
              </Form.Group>
              <Form.Group controlId="password">
                <Form.Label>Password</Form.Label>
                <Form.Control type="password" placeholder="Enter your password" value={password} onChange={handlePasswordChange} />
              </Form.Group>
              <Button onClick={signIn} variant="primary" type="submit">
                Connect
              </Button>
            </Form>
          </Card.Body>
        </Card>
      </Container>
    </div>
  );
};

export default Login;

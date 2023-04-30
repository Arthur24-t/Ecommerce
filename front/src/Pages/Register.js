import React from 'react';
import { Card, Container, Form, Button } from 'react-bootstrap';
import Navigation from '../Component/Navigation';
import { useState } from 'react';
import axios from 'axios';

const Register = () => {

  const [login, setLogin] = useState('');
  const [firstname, setFirstname] = useState('');
  const [lastname, setLastname] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [register, setRegister] = useState('');

  const handleSubmit = (event) => {
    event.preventDefault();
    const userData = {
      login: login,
      firstname: firstname,
      lastname: lastname,
      email: email,
      password: password
    };
    axios.post(`http://127.0.0.1:8000/api/register`, userData)
      .then(response => {
        setRegister(response.data);
      })
      .catch(error => {
        console.error('Erreur lors de la récupération des données :', error);
      });
  };

  return (
    <div>
      <Navigation />
      <Container className="d-flex align-items-center justify-content-center vh-100">
        <Card className="mx-auto" style={{ width: '22rem' }}>
          <Card.Body>
            <Card.Title>Register</Card.Title>
            <Form onSubmit={handleSubmit}>
              <Form.Group controlId="login">
                <Form.Label>Login</Form.Label>
                <Form.Control
                  type="text"
                  placeholder="Entrez votre Login"
                  value={login}
                  onChange={(e) => setLogin(e.target.value)}
                />
              </Form.Group>
              <Form.Group controlId="firstname">
                <Form.Label>Firstname</Form.Label>
                <Form.Control
                  type="text"
                  placeholder="Entrez votre Firstname"
                  value={firstname}
                  onChange={(e) => setFirstname(e.target.value)}
                />
              </Form.Group>
              <Form.Group controlId="lastname">
                <Form.Label>Lastname</Form.Label>
                <Form.Control
                  type="text"
                  placeholder="Entrez votre Lastname"
                  value={lastname}
                  onChange={(e) => setLastname(e.target.value)}
                />
              </Form.Group>
              <Form.Group controlId="email">
                <Form.Label>Email</Form.Label>
                <Form.Control
                  type="email"
                  placeholder="Entrez votre email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                />
              </Form.Group>
              <Form.Group controlId="password">
                <Form.Label>Mot de passe</Form.Label>
                <Form.Control
                  type="password"
                  placeholder="Entrez votre mot de passe"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                />
              </Form.Group>
              <Button variant="primary" type="submit">
                Register
              </Button>
            </Form>
          </Card.Body>
        </Card>
      </Container>
    </div>
  );
};

export default Register;

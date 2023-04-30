import React from 'react';
import { Container, Nav, Navbar, Button } from 'react-bootstrap';

const Navigation = () => {
  const isLoggedIn = !!localStorage.getItem('token');

  const handleLogout = () => {
    localStorage.removeItem('token');
    window.location.href = '/login'; 
  };

  return (
    <Navbar bg="dark" variant="dark" sticky='top'>
      <Container>
        <Navbar.Brand href="/">Le Shop</Navbar.Brand>
        <Nav className="mx-auto">
        </Nav>
        <Nav>
          <Button variant="outline-success"><Nav.Link href="/panier">Panier</Nav.Link></Button>
          {isLoggedIn ? null : <Button variant="link"><Nav.Link href="/register">Register</Nav.Link></Button>}
          {isLoggedIn ? null : <Button variant="link"><Nav.Link href="/login">Login</Nav.Link></Button>}
          {isLoggedIn && <Button variant="link" onClick={handleLogout}>Logout</Button>}
        </Nav>
      </Container>
    </Navbar>
  );
};

export default Navigation;

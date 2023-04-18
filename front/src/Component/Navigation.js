import React from 'react';  
import { Container, Nav, Navbar, Button } from 'react-bootstrap';


const Navigation = () => {
    return (
<Navbar bg="dark" variant="dark" sticky='top'>
        <Container> 
          <Navbar.Brand href="/">Amazong</Navbar.Brand>
          <Nav className="mx-auto">
                </Nav>
                <Nav>
            <Button variant="outline-success"><Nav.Link href="/panier">Panier</Nav.Link></Button>        
          </Nav>
        </Container>
      </Navbar>
    );
};

export default Navigation;
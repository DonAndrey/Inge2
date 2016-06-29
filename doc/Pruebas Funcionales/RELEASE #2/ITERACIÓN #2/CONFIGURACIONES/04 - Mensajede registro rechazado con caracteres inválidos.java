package com.example.tests;

import java.util.regex.Pattern;
import java.util.concurrent.TimeUnit;
import org.junit.*;
import static org.junit.Assert.*;
import static org.hamcrest.CoreMatchers.*;
import org.openqa.selenium.*;
import org.openqa.selenium.firefox.FirefoxDriver;
import org.openqa.selenium.support.ui.Select;

public class MensajeCorreoRegistroRechazadoInvalido {
  private WebDriver driver;
  private String baseUrl;
  private boolean acceptNextAlert = true;
  private StringBuffer verificationErrors = new StringBuffer();

  @Before
  public void setUp() throws Exception {
    driver = new FirefoxDriver();
    baseUrl = "http://localhost:1337/";
    driver.manage().timeouts().implicitlyWait(30, TimeUnit.SECONDS);
  }

  @Test
  public void testMensajeCorreoRegistroRechazadoInvalido() throws Exception {
    driver.get(baseUrl + "/prot/src/protea_reservations/pages/home");
    driver.findElement(By.linkText("Configuraciones")).click();
    driver.findElement(By.linkText("Configuraciones")).click();
    driver.findElement(By.id("configurations-registration-rejected-message")).clear();
    driver.findElement(By.id("configurations-registration-rejected-message")).sendKeys("Su solicitud de registro ha sido rechazada. =");
    driver.findElement(By.id("configurations-registration-rejected-message")).clear();
    driver.findElement(By.id("configurations-registration-rejected-message")).sendKeys("Su solicitud de registro ha sido rechazada. =");
    driver.findElement(By.cssSelector("button.btn.btn-success")).click();
    driver.findElement(By.cssSelector("button.btn.btn-success")).click();
    try {
      assertEquals("Debe usar solamente letras, números o signos (,;.¡!-()@).", driver.findElement(By.cssSelector("div.error-message")).getText());
    } catch (Error e) {
      verificationErrors.append(e.toString());
    }
  }

  @After
  public void tearDown() throws Exception {
    driver.quit();
    String verificationErrorString = verificationErrors.toString();
    if (!"".equals(verificationErrorString)) {
      fail(verificationErrorString);
    }
  }

  private boolean isElementPresent(By by) {
    try {
      driver.findElement(by);
      return true;
    } catch (NoSuchElementException e) {
      return false;
    }
  }

  private boolean isAlertPresent() {
    try {
      driver.switchTo().alert();
      return true;
    } catch (NoAlertPresentException e) {
      return false;
    }
  }

  private String closeAlertAndGetItsText() {
    try {
      Alert alert = driver.switchTo().alert();
      String alertText = alert.getText();
      if (acceptNextAlert) {
        alert.accept();
      } else {
        alert.dismiss();
      }
      return alertText;
    } finally {
      acceptNextAlert = true;
    }
  }
}

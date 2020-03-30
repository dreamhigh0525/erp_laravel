CREATE FUNCTION invoice()
  RETURNS trigger AS
$$
BEGIN
    UPDATE products
        SET stock = stock - NEW.qty
        WHERE products.id = NEW.name;
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER reduce_stock
  AFTER INSERT
  ON invoice_products
  FOR EACH ROW
  EXECUTE PROCEDURE invoice();

CREATE FUNCTION purchase()
  RETURNS trigger AS
$$
BEGIN
    UPDATE products
        SET stock = stock + NEW.qty
        WHERE products.id = NEW.name;
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER reduce_stock_purchase
  AFTER INSERT
  ON purchase_products
  FOR EACH ROW
  EXECUTE PROCEDURE purchase();

CREATE OR REPLACE FUNCTION retur_purchase()
  RETURNS trigger AS
$$
BEGIN
    INSERT into po_logs (id,purchase_no,name, qty) VALUES (OLD.ID,OLD.purchase_id,OLD.NAME,OLD.qty);
RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER reduce_stock_delete_purchase
  AFTER DELETE
  ON purchase_products
  FOR EACH ROW
  EXECUTE PROCEDURE retur_purchase();

CREATE OR REPLACE FUNCTION retur_invoice()
  RETURNS trigger AS
$$
BEGIN
    INSERT into inv_logs (id,invoice_no,name, qty) VALUES (OLD.ID,OLD.invoice_id,OLD.NAME,OLD.qty);
RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER reduce_stock_delete_invoice
  AFTER DELETE
  ON invoice_products
  FOR EACH ROW
  EXECUTE PROCEDURE retur_invoice();



CREATE OR REPLACE FUNCTION return_purchase()
  RETURNS trigger AS
$$
BEGIN
    UPDATE products
        SET stock = stock - NEW.qty
        WHERE products.id = NEW.name;
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER plus_stock_logs_purchase
  AFTER Insert
  ON po_logs
  FOR EACH ROW
  EXECUTE PROCEDURE return_purchase();


CREATE OR REPLACE FUNCTION return_invoice()
  RETURNS trigger AS
$$
BEGIN
    UPDATE products
        SET stock = stock + NEW.qty
        WHERE products.id = NEW.name;
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER plus_stock_logs_invoice
  AFTER Insert
  ON inv_logs
  FOR EACH ROW
  EXECUTE PROCEDURE return_invoice();

CREATE OR REPLACE FUNCTION customer_dept()
  RETURNS trigger AS
$$
BEGIN
    INSERT into customer_debt (invoice_no,customer_id,invoice_date,due_date,total,payment,over,status)
    VALUES (NEW.invoice_no,NEW.client,NEW.invoice_date,NEW.due_date,NEW.grand_total,0,0,'UNPAID');
    UPDATE res_customers
        SET debit_limit = debit_limit + NEW.grand_total
        WHERE res_customers.id = NEW.client;
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER create_customer_dept
  AFTER Insert
  ON invoices
  FOR EACH ROW
  EXECUTE PROCEDURE customer_dept();

drop trigger create_customer_dept on invoices

CREATE OR REPLACE FUNCTION partner_credit()
  RETURNS trigger AS
$$
BEGIN
    INSERT into partner_credit (purchase_no,partner_id,purchase_date,due_date,total,payment,over,status)
    VALUES (NEW.purchase_no,NEW.client,NEW.purchase_date,NEW.due_date,NEW.grand_total,0,0,'UNPAID');
    UPDATE res_partners
        SET debit_limit = debit_limit + NEW.grand_total
        WHERE res_partners.id = NEW.client;
    RETURN NEW;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER create_partner_credit
  AFTER Insert
  ON purchases
  FOR EACH ROW
  EXECUTE PROCEDURE partner_credit();


namespace DCA.Model.Contracts;

public interface IWithdrawer
{
    public bool Withdraw(Bitcoin amount, Address destination);
}

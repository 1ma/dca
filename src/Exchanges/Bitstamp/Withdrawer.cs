using DCA.Model;
using DCA.Model.Contracts;

namespace DCA.Exchanges.Bitstamp;

public class Withdrawer : IWithdrawer
{
    public bool Withdraw(Bitcoin amount, Address destination)
    {
        return true;
    }
}
